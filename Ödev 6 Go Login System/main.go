package main

import (
	"bufio"
	"fmt"
	"log"
	"os"
	"strings"
	"time"
)

type Kullanici struct {
	KullaniciAdi string
	Sifre        string
	Rol          string // admin veya müşteri
}

var kullanicilar = []Kullanici{
	{"admin", "admin123", "admin"}, // Varsayılan admin kullanıcısı
}
var logDosyasi = "log.txt"

// Log kaydetme
func logKaydet(mesaj string) {
	file, err := os.OpenFile(logDosyasi, os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		log.Printf("Log kaydedilirken hata oluştu: %v\n", err)
		return
	}
	defer file.Close()

	logMesaj := fmt.Sprintf("%s - %s\n", time.Now().Format("2006-01-02 15:04:05"), mesaj)
	if _, err := file.WriteString(logMesaj); err != nil {
		log.Printf("Log kaydedilirken hata oluştu: %v\n", err)
	}
}

// Kullanıcı doğrulama
func kullaniciDogrula(kullaniciAdi, sifre string) (*Kullanici, bool) {
	for _, k := range kullanicilar {
		if k.KullaniciAdi == kullaniciAdi && k.Sifre == sifre {
			return &k, true
		}
	}
	return nil, false
}

// Kayıt
func kullaniciKayit() {
	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("Kayıt Ol")
	fmt.Print("Kullanıcı Adı: ")
	scanner.Scan()
	kullaniciAdi := strings.ToLower(scanner.Text())

	// Kullanıcı adı benzersiz mi kontrol et
	for _, k := range kullanicilar {
		if k.KullaniciAdi == kullaniciAdi {
			fmt.Println("Bu kullanıcı adı zaten mevcut, başka bir ad seçiniz.")
			return
		}
	}

	fmt.Print("Şifre: ")
	scanner.Scan()
	sifre := scanner.Text()

	var rol string
	fmt.Print("Rol (0- Admin, 1- Müşteri): ")
	scanner.Scan()
	switch scanner.Text() {
	case "0":
		rol = "admin"
	case "1":
		rol = "musteri"
	default:
		fmt.Println("Geçersiz rol seçimi. Kayıt işlemi iptal edildi.")
		return
	}

	kullanicilar = append(kullanicilar, Kullanici{KullaniciAdi: kullaniciAdi, Sifre: sifre, Rol: rol})
	logKaydet(fmt.Sprintf("Yeni kullanıcı kaydedildi: %s (Rol: %s)", kullaniciAdi, rol))
	fmt.Println("Kayıt başarılı.")
}

// Admin yetkileri
func adminYetkileri() {
	scanner := bufio.NewScanner(os.Stdin)

	for {
		fmt.Println("Admin İşlemleri:")
		fmt.Println("1. Müşteri Ekle")
		fmt.Println("2. Müşteri Sil")
		fmt.Println("3. Logları Listele")
		fmt.Println("0. Çıkış")
		scanner.Scan()
		secim := scanner.Text()

		switch secim {
		case "1":
			kullaniciKayit()
		case "2":
			fmt.Print("Silinecek Müşteri Kullanıcı Adı: ")
			scanner.Scan()
			kullaniciAdi := scanner.Text()
			kullaniciSil(kullaniciAdi)
		case "3":
			logListele();
		case "0":
			return
		default:
			fmt.Println("Geçersiz seçim.")
		}
	}
}

// Kullanıcı silme
func kullaniciSil(kullaniciAdi string) {
	for i, k := range kullanicilar {
		if k.KullaniciAdi == kullaniciAdi && k.Rol == "musteri" {
			kullanicilar = append(kullanicilar[:i], kullanicilar[i+1:]...) // Kullanıcıyı sil
			logKaydet(fmt.Sprintf("Kullanıcı silindi: %s", kullaniciAdi))
			fmt.Printf("%s adlı müşteri başarıyla silindi.\n", kullaniciAdi)
			return
		}
	}
	fmt.Println("Kullanıcı bulunamadı veya yalnızca admin silinebilir.")
}

// Logları listeleme
func logListele() {
	file, err := os.Open(logDosyasi)
	if err != nil {
		log.Printf("Log dosyası okunurken hata oluştu: %v\n", err)
		return
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	fmt.Println("Log Kayıtları:")
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}
	if err := scanner.Err(); err != nil {
		log.Printf("Log dosyası okunurken hata oluştu: %v\n", err)
	}
}

// Müşteri yetkileri
func musteriYetkileri(kullanici *Kullanici) {
	scanner := bufio.NewScanner(os.Stdin)

	for {
		fmt.Println("Müşteri İşlemleri:")
		fmt.Println("1. Profil Görüntüle")
		fmt.Println("2. Şifre Değiştir")
		fmt.Println("0. Çıkış")
		scanner.Scan()
		secim := scanner.Text()

		switch secim {
		case "1":
			fmt.Printf("Kullanıcı Adı: %s, Rol: %s\n", kullanici.KullaniciAdi, kullanici.Rol)
		case "2":
			fmt.Print("Yeni Şifre: ")
			scanner.Scan()
			yeniSifre := scanner.Text()
			kullanici.Sifre = yeniSifre
			logKaydet(fmt.Sprintf("Kullanıcı şifre değiştirdi: %s", kullanici.KullaniciAdi))
			fmt.Println("Şifre değişikliği başarılı.")
		case "0":
			return
		default:
			fmt.Println("Geçersiz seçim.")
		}
	}
}

// Giriş
func girisYap() {
	scanner := bufio.NewScanner(os.Stdin)

	fmt.Print("Kullanıcı Adı: ")
	scanner.Scan()
	kullaniciAdi := strings.ToLower(scanner.Text())

	fmt.Print("Şifre: ")
	scanner.Scan()
	sifre := scanner.Text()

	kullanici, dogruMu := kullaniciDogrula(kullaniciAdi, sifre)
	if !dogruMu {
		logKaydet(fmt.Sprintf("Hatalı giriş denemesi: %s", kullaniciAdi))
		fmt.Println("Hatalı kullanıcı adı veya şifre.")
		return
	}

	logKaydet(fmt.Sprintf("Başarılı giriş: %s (Rol: %s)", kullanici.KullaniciAdi, kullanici.Rol))

	if kullanici.Rol == "admin" {
		adminYetkileri()
	} else if kullanici.Rol == "musteri" {
		musteriYetkileri(kullanici)
	}
}

func main() {
	scanner := bufio.NewScanner(os.Stdin)
	for {
		fmt.Println("1. Giriş Yap")
		fmt.Println("2. Kayıt Ol")
		fmt.Println("0. Çıkış")
		scanner.Scan()
		secim := scanner.Text()

		switch secim {
		case "1":
			girisYap()
		case "2":
			kullaniciKayit()
		case "0":
			fmt.Println("Çıkış yapılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçim.")
		}
	}
}
