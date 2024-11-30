package main

import (
	"encoding/json"
	"bufio"
	"fmt"
	"log"
	"os"
	"time"
)

type Kullanici struct {
	KullaniciAdi string `json:"kullaniciAdi"`
	Sifre        string `json:"sifre"`
	Rol          string `json:"rol"`
}

var kullanicilar []Kullanici

func main() {
	kullanicilariYukle()
	createLogFiles()
	login()
	logFunc("Program kapandı")
}

func createLogFiles() {
	// Log dosyasının oluşturulması (yoksa)
	file, err := os.OpenFile("log.txt", os.O_CREATE|os.O_APPEND|os.O_WRONLY, 0644)
	if err != nil {
		log.Fatalf("Log dosyası oluşturulurken hata oluştu: %v", err)
	}
	defer file.Close()
}

func logFunc(message string) {
	// Log dosyasına mesaj yazma
	file, err := os.OpenFile("log.txt", os.O_APPEND|os.O_WRONLY, 0644)
	if err != nil {
		log.Fatalf("Log kaydedilirken hata oluştu: %v", err)
	}
	defer file.Close()

	logMessage := fmt.Sprintf("%s - %s\n", time.Now().Format("2006-01-02 15:04:05"), message)
	_, err = file.WriteString(logMessage)
	if err != nil {
		log.Fatalf("Log kaydedilirken hata oluştu: %v", err)
	}
}

func kullanicilariYukle() {
	// JSON dosyasından kullanıcıları yükleme
	file, err := os.Open("kullanicilar.json")
	if err != nil {
		log.Fatalf("Kullanıcılar dosyası açılırken hata: %v", err)
	}
	defer file.Close()

	decoder := json.NewDecoder(file)
	if err := decoder.Decode(&kullanicilar); err != nil {
		log.Fatalf("Kullanıcılar dosyasından veri yüklenemedi: %v", err)
	}
}

var usernameInput, passwordInput string

func login() {
	var role int
	fmt.Print("\033[H\033[2J")
	fmt.Println("Hoşgeldiniz!!")
	fmt.Println("0: Admin Girişi")
	fmt.Println("1: Müşteri Girişi")
	fmt.Print("Rolünüzü seçin: ")
	fmt.Scan(&role)

	if role == 0 {
		adminCheck()
	} else if role == 1 {
		customerCheck()
	} else {
		fmt.Println("Geçersiz rol seçimi!")
		logFunc("Geçersiz rol seçildi")
		login()
	}
}

func adminCheck() {
	for {
		fmt.Print("Admin Kullanıcı Adı: ")
		fmt.Scan(&usernameInput)
		fmt.Print("Admin Şifre: ")
		fmt.Scan(&passwordInput)

		// Admin kontrolü
		for _, kullanici := range kullanicilar {
			if kullanici.KullaniciAdi == usernameInput && kullanici.Sifre == passwordInput && kullanici.Rol == "admin" {
				logFunc("Admin giriş yaptı")
				fmt.Println("\nHoş geldiniz admin")
				adminMenu()
				return
			}
		}
		logFunc("Admin için hatalı giriş")
		fmt.Println("Kullanıcı adı veya şifre hatalı!")
	}
}

func customerCheck() {
	for {
		fmt.Print("Müşteri Kullanıcı Adı: ")
		fmt.Scan(&usernameInput)
		fmt.Print("Müşteri Şifre: ")
		fmt.Scan(&passwordInput)

		// Müşteri kontrolü
		for _, kullanici := range kullanicilar {
			if kullanici.KullaniciAdi == usernameInput && kullanici.Sifre == passwordInput && kullanici.Rol == "müşteri" {
				logFunc(usernameInput + " giriş yaptı")
				fmt.Println("\nHoş geldiniz " + usernameInput)
				customerMenu()
				return
			}
		}
		logFunc("Müşteri için hatalı giriş")
		fmt.Println("Kullanıcı adı veya şifre hatalı!")
	}
}

func adminMenu() {
	for {
		fmt.Println("\nAdmin Menüsü:")
		fmt.Println("1- Müşteri ekleme")
		fmt.Println("2- Müşteri Silme")
		fmt.Println("3- Log Listeleme")
		fmt.Println("4- Çıkış")
		var secim string
		fmt.Print("Seçim yapınız: ")
		fmt.Scan(&secim)

		if secim == "1" {
			addCustomer()
		} else if secim == "2" {
			deleteCustomer()
		} else if secim == "3" {
			showLogs()
		} else if secim == "4" {
			fmt.Println("Çıkılıyor...")
			return
		} else {
			fmt.Println("Geçersiz seçim!")
		}
	}
}

func addCustomer() {
	var username, password string
	fmt.Print("Yeni müşteri kullanıcı adı: ")
	fmt.Scan(&username)
	fmt.Print("Yeni müşteri şifresi: ")
	fmt.Scan(&password)

	// Yeni müşteri ekle
	yeniKullanici := Kullanici{
		KullaniciAdi: username,
		Sifre:        password,
		Rol:          "müşteri",
	}
	kullanicilar = append(kullanicilar, yeniKullanici)
	saveUsers()
	fmt.Println("Müşteri başarıyla eklendi.")
}

func deleteCustomer() {
	var usernameToDelete string
	fmt.Print("Silinecek müşteri kullanıcı adı: ")
	fmt.Scan(&usernameToDelete)

	// Kullanıcıyı sil
	for i, kullanici := range kullanicilar {
		if kullanici.KullaniciAdi == usernameToDelete {
			kullanicilar = append(kullanicilar[:i], kullanicilar[i+1:]...)
			saveUsers()
			fmt.Println("Müşteri başarıyla silindi.")
			return
		}
	}
	fmt.Println("Müşteri bulunamadı.")
}

func showLogs() {
	file, err := os.Open("log.txt")
	if err != nil {
		log.Fatalf("Log dosyası açılamadı: %v", err)
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}
	if err := scanner.Err(); err != nil {
		log.Fatal(err)
	}
}

func saveUsers() {
	// Kullanıcıları JSON dosyasına kaydet
	file, err := os.OpenFile("kullanicilar.json", os.O_RDWR|os.O_CREATE|os.O_TRUNC, 0644)
	if err != nil {
		log.Fatalf("Kullanıcılar dosyası açılamadı: %v", err)
	}
	defer file.Close()

	encoder := json.NewEncoder(file)
	encoder.SetIndent("", "  ")
	if err := encoder.Encode(kullanicilar); err != nil {
		log.Fatalf("Kullanıcılar dosyasına yazılamadı: %v", err)
	}
}


func customer() {

}

func showProfile() {
	logFunc(usernameInput + "profilini görüntüledi")
	fmt.Println("Mevcut kullanıcı: " + usernameInput + ":" + passwordInput)
}

func customerMenu() {
	for {
		fmt.Println("\nMüşteri Menüsü:")
		fmt.Println("1- Profili görüntüle")
		fmt.Println("2- Şifreyi değiştirme")
		fmt.Println("3- Çıkış")
		var secim string
		fmt.Scan(&secim)
		fmt.Print("Seçim Yapınız: ")
		if secim == "1" {
			fmt.Println("Profili görüntüle seçildi\n")
			logFunc("Profili görüntüle seçildi")
			showProfile()
		} else if secim == "2" {
			fmt.Println("Şifreyi değiştirme seçildi\n")
			logFunc("Şifreyi değiştirme seçildi")
			changePassword()
		} else if secim == "3" {
			fmt.Println("Çıkış Yapılıyor..")
			logFunc(usernameInput + " çıkış yaptı")
			login()
		} else {
			fmt.Println("Geçersiz Seçim!!")
			logFunc("Geçersiz Seçim")
			customer()
		}

	}
}

func changePassword() {
	var usernameInput, currentPassword, newPassword, confirmPassword string

	fmt.Print("Kullanıcı adı: ")
	fmt.Scan(&usernameInput)

	var user *Kullanici
	for i := range kullanicilar {
		if kullanicilar[i].KullaniciAdi == usernameInput {
			user = &kullanicilar[i]
			break
		}
	}

	if user == nil {
		fmt.Println("Kullanıcı bulunamadı!")
		logFunc(fmt.Sprintf("Kullanıcı '%s' adıyla giriş yapılmaya çalışıldı, ancak bulunamadı.", usernameInput))
		return
	}

	fmt.Print("Mevcut şifrenizi girin: ")
	fmt.Scan(&currentPassword)

	if user.Sifre != currentPassword {
		fmt.Println("Mevcut şifre hatalı!")
		logFunc(fmt.Sprintf("Kullanıcı '%s' yanlış şifre girmeye çalıştı.", usernameInput))
		return
	}

	fmt.Print("Yeni şifrenizi girin: ")
	fmt.Scan(&newPassword)

	fmt.Print("Yeni şifrenizi onaylayın: ")
	fmt.Scan(&confirmPassword)

	if newPassword != confirmPassword {
		fmt.Println("Yeni şifreler uyuşmuyor!")
		logFunc(fmt.Sprintf("Kullanıcı '%s' için yeni şifreler uyuşmadı.", usernameInput))
		return
	}

	user.Sifre = newPassword
	fmt.Println("Şifreniz başarıyla değiştirildi!")
	logFunc(fmt.Sprintf("Kullanıcı '%s' şifresini başarıyla değiştirdi.", usernameInput))

	kullanicilariKaydet()
}

func kullanicilariKaydet() {
	file, err := os.Create("kullanicilar.json")
	if err != nil {
		log.Fatalf("Kullanıcılar dosyası kaydedilirken hata oluştu: %v", err)
	}
	defer file.Close()

	encoder := json.NewEncoder(file)
	if err := encoder.Encode(kullanicilar); err != nil {
		log.Fatalf("Kullanıcılar dosyasına veri yazılırken hata oluştu: %v", err)
	}
}
