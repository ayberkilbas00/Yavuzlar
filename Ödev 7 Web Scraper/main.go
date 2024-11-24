package main

import (
	"fmt"
	"net/http"
	"os"
	"strings"
	"github.com/PuerkitoBio/goquery"
)
func main() {
	args := os.Args

	if len(args) == 1 {
		help()
		return
	}
	if len(args) == 2 {
		switch args[1] {
		case "-1":
			theHackerNews()
		case "-2":
			techCrunch()
		case "-3":
			nefisyemekTarifleriCom()
		case "-4":
			os.Exit(0)
		}
	}
}

func help() {
	fmt.Printf(`
Usage:
  go run project.go -1,-4

Seçenekler:
-1 "TheHackerNews"ten başlıkları, tarihleri ​​ve açıklamaları getirir
-2 "TechCrunch"tan başlıkları, tarihleri ​​ve açıklamaları getirir
-3 "Nefis Yemek Tariflerinden"den başlıkları, tarihleri ​​ve açıklamaları getirir
-4 Programdan çıkar.
`)
}

func theHackerNews() {
	res, err := http.Get("https://thehackernews.com/")
	if err != nil {
		fmt.Println("Error:", err)
		return
	}
	if res.StatusCode != 200 {
		fmt.Println("Error", res.StatusCode)
		return
	}

	title := []string{}
	date := []string{}
	desc := []string{}

	doc, _ := goquery.NewDocumentFromReader(res.Body)

	doc.Find("h2.home-title").Each(func(i int, selection *goquery.Selection) {
		title = append(title, strings.TrimSpace(selection.Text()))
	})

	doc.Find("span.h-datetime").Each(func(i int, selection *goquery.Selection) {
		rawDate := strings.TrimSpace(selection.Text())
		cleanDate := rawDate[3:]
		date = append(date, cleanDate)
	})

	doc.Find("div.home-desc").Each(func(i int, selection *goquery.Selection) {
		desc = append(desc, strings.TrimSpace(selection.Text()))
	})

	for i := 0; i < len(title) && i < len(date) && i < len(desc); i++ {
		output := fmt.Sprintf("Number: %d\nTitle: %s\nDate: %s\nDescription: %s\n---------------------------\n", i+1, title[i], date[i], desc[i])
		fmt.Print(output)
		fileWrite(output, "output/thehackernews.com")
	}
}

func techCrunch() {
	res, err := http.Get("https://techcrunch.com/")
	if err != nil {
		fmt.Println("Error:", err)
		return
	}
	if res.StatusCode != 200 {
		fmt.Println("Error", res.StatusCode)
		return
	}

	title := []string{}
	date := []string{}
	desc := []string{}

	doc, _ := goquery.NewDocumentFromReader(res.Body)

	doc.Find("h2.wp-block-heading").Each(func(i int, selection *goquery.Selection) {
		title = append(title, strings.TrimSpace(selection.Text()))
	})

	doc.Find("time").Each(func(i int, selection *goquery.Selection) {
		date = append(date, strings.TrimSpace(selection.Text()))
	})

	doc.Find("div.wp-block-group p").Each(func(i int, selection *goquery.Selection) {
		desc = append(desc, strings.TrimSpace(selection.Text()))
	})

	for i := 0; i < len(title) && i < len(date) && i < len(desc); i++ {
		output := fmt.Sprintf("Number: %d\nTitle: %s\nDate: %s\nDescription: %s\n---------------------------\n", i+1, title[i], date[i], desc[i])
		fmt.Print(output)
		fileWrite(output, "output/techcrunch.com")
	}
}

func nefisyemekTarifleriCom() {
	client := &http.Client{}
	req, err := http.NewRequest("GET", "https://www.nefisyemektarifleri.com/", nil)
	if err != nil {
		fmt.Println("Error:", err)
		return
	}

	req.Header.Add("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36")

	res, err := client.Do(req)
	if err != nil {
		fmt.Println("Error:", err)
		return
	}
	if res.StatusCode != 200 {
		fmt.Println("Error", res.StatusCode)
		return
	}

	title := []string{}
	imageURL := []string{}
	doc, _ := goquery.NewDocumentFromReader(res.Body)
	doc.Find("span.element-name").Each(func(i int, selection *goquery.Selection) {
		title = append(title, strings.TrimSpace(selection.Text()))
	})
	doc.Find("img[data-lazy-src]").Each(func(i int, selection *goquery.Selection) {
		src, _ := selection.Attr("data-lazy-src")
		imageURL = append(imageURL, strings.TrimSpace(src))
	})
	for i := 0; i < len(title) && i < len(imageURL); i++ {
		output := fmt.Sprintf("Number: %d\nTitle: %s\nImage URL: %s\n---------------------------\n", i+1, title[i], imageURL[i])
		fmt.Print(output)
		fileWrite(output, "output/nefisyemektarifleri.com")
	}
}
func fileWrite(output, site string) {
	file, err := os.OpenFile(site+".txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Error opening file:", err)
		return
	}
	defer file.Close()
	_, err = file.WriteString(output)
	if err != nil {
		fmt.Println("Error writing to file:", err)
	}
}