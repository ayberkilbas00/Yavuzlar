let questions = [
    {
      question: "Türkiyenin başkenti neresidir?",
      answers: [
        { text: "Ankara", correct: true },
        { text: "İstanbul", correct: false },
        { text: "İzmir", correct: false },
        { text: "Bursa", correct: false }
      ],
       level:"Kolay"
    },
    {
      question: "En büyük gezegen hangisidir?",
      answers: [
        { text: "Mars", correct: false },
        { text: "Venüs", correct: false },
        { text: "Jüpiter", correct: true },
        { text: "Dünya", correct: false },

      ],
      level:"Zor"
    },
    {
      question: "En hızlı kara hayvanı nedir?",
      answers: [
        { text: "Aslan", correct: false },
        { text: "Çita", correct: true },
        { text: "Zebra", correct: false },
        { text: "Geyik", correct: false }
      ],
      level:"Orta"
    }
];


const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");
const adminPanelElement = document.querySelector("#admin-panel");
const appElement = document.querySelector(".app");
const easyQuestionsElement = document.querySelector(".easy-questions");
const adminGoBackButton = document.querySelector(".admin-go-back-button");
const adminAddQuestionButton = document.querySelector(".admin-add-question-button");
const questionLevel = document.querySelector(".question-level");
const searchQuestion = document.querySelector(".admin-search-question-button");
const searchQuestionSection = document.querySelector(".admin-search-question-section");
const searchBoxButton = document.querySelector(".search-box-button");
const results = document.querySelector(".results");


let currentQuestionIndex = 0;
let score = 0;
let userQuestionLevelSelection;

function startQuiz () {
    currentQuestionIndex = 0;
    score = 0;
    nextButton.innerHTML = "Next";
    showQuestion();
    easyQuestionsElement.style.display = "none";
    adminAddQuestionButton.classList.add("hidden");
    searchQuestionSection.classList.add("hidden");
    adminGoBackButton.enabled = false;
}

function showQuestion() {
   resetState();
  let randomNumber = Math.floor(Math.random() * questions.length)
  let currentQuestion = questions[randomNumber];
  let questionNo = currentQuestionIndex + 1;
  questionElement.innerHTML = `${questionNo} . ${currentQuestion.question}`;
  questionLevel.innerHTML = "Zorluk: " + questions[randomNumber].level;

  currentQuestion.answers.forEach(function(answer, indx){
     const button = document.createElement("button");
     button.innerHTML = answer.text
     button.classList.add("btn");
     button.style.fontSize = '18px';
     button.style.fontWeight = '700';
     answerButtons.appendChild(button);
     if(answer.correct) {
        button.dataset.correct = answer.correct;
     }
     button.addEventListener("click", selectAnswer);
  })
}

function resetState() {
    nextButton.style.display = "none";
    while(answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}

function selectAnswer(e) {
const selectedBtn = e.target;
const isCorrect = selectedBtn.dataset.correct === "true";

if(isCorrect) {
    selectedBtn.classList.add("correct");
    score++;
}
else {
    selectedBtn.classList.add("incorrect")
}
 Array.from(answerButtons.children).forEach(function(button) {
    if(button.dataset.correct === "true") {
        button.classList.add("correct");
    } 
    button.disabled = true;
 })
 nextButton.style.display = "block";
}

function showScore () {
    resetState();
    questionElement.innerHTML = `${questions.length} sorudan ${score} kadarını doğru yaptınız! `;
    nextButton.innerHTML = "Play again";
    nextButton.style.display = "block";
}

function handleNextButton() {
currentQuestionIndex++;

 if(currentQuestionIndex < questions.length) {
          showQuestion();
 } else {
    showScore();
 }
}

nextButton.addEventListener("click" , () => {
    if(currentQuestionIndex < questions.length) {
        handleNextButton();
    } else {
        startQuiz();
    }
})


adminAddQuestionButton.addEventListener("click", () => adminAddQuestionFunction() )
searchQuestion.addEventListener("click", () => searchQuestionAdminButton())



document.querySelector('#form').addEventListener('submit', function(event) {
  event.preventDefault(); 
  let matches = [];
  let foundQuestionTitle = document.querySelector(".result-question");
  let foundAnswerButtons = document.querySelectorAll(".result-answers");
  let query = document.querySelector('#query').value;
  console.log('Search query:', query); 

  questions.forEach(function(el,indx) {
    if(el.question.includes(query)) {
      matches.push(el) 
    } 

    if(matches.length < 2 && matches.length > 0) {

      console.log(matches[0].question)
      foundQuestionTitle.innerHTML = matches[0].question
      foundQuestionTitle.classList.remove("hidden")

      foundAnswerButtons.forEach(function(el,indx) {
       el.innerHTML = matches[0].answers[indx].text;
       el.classList.remove("hidden")
      })
    } 

    if(matches.length < 1 ) {
      foundQuestionTitle.innerHTML = "Soru bulunamadı"
      
      foundAnswerButtons.forEach(function(el,indx) {
        el.classList.add("hidden")
       })
    }


  }) 
});


function searchQuestionSearchArea() {
 //const search = document.querySelector(".search")
 console.log("sa")
//console.log(search.value)

}
function searchQuestionAdminButton() {
  easyQuestionsElement.style.display = "none";
  adminGoBackButton.classList.add("hidden");
  adminAddQuestionButton.classList.add("hidden");
  searchQuestionSection.classList.remove("hidden");

  
}

function adminAddQuestionFunction() {
   easyQuestionsElement.style.display = "none";
   adminGoBackButton.classList.add("hidden");
   adminAddQuestionButton.classList.add("hidden");
  

   let answerTextAreaOptions = [];
   let radioButtons = [];
   let questionLevelRadioButtons = [];

   let answersArea;
   let selectCorrectAnswer;

   let questionLevelInput;
   let questionLevelTextsArray;
 
  questionLevelTextsArray = ["Kolay", "Orta", "Zor"];

    const containerDiv = document.createElement("div");   
    const questionArea = document.createElement("textarea"); 
    const submitButton = document.createElement("button"); 
    submitButton.innerHTML = "Gönder";
    document.body.appendChild(containerDiv);
    containerDiv.appendChild(questionArea);
   
    questionArea.innerHTML = "Soru"
    questionArea.style.display ="block";
    containerDiv.classList.add("admin-add-question-section")
    submitButton.classList.add("btn")
    questionArea.classList.add("custom-textarea-question");
    
    
    for(let i = 0; i < 4; i++) {
      answersArea = document.createElement("textarea"); 
      selectCorrectAnswer = document.createElement("input");
      selectCorrectAnswer.type = 'radio';
      selectCorrectAnswer.name = 'correctAnswer'
      answersArea.style.display = "block";
      answersArea.classList.add("custom-textarea-answers");

      answerTextAreaOptions.push(answersArea);
      radioButtons.push(selectCorrectAnswer);
      
      containerDiv.appendChild(answersArea)
      containerDiv.appendChild(selectCorrectAnswer);

      switch(i) {
        case 0: answersArea.innerHTML = "A şıkkı"
        break;

        case 1: answersArea.innerHTML = "B şıkkı"
        break;

        case 2: answersArea.innerHTML = "C şıkkı"
        break;

        case 3: answersArea.innerHTML = "D şıkkı"
        
        break;
      }
      
    }

    for(let i = 0; i < 3; i++) {
      questionLevelInput = document.createElement("input");
      questionLevelTexts = document.createElement("h3");
      questionLevelTexts.classList.add("h3");
      questionLevelTexts.innerHTML = questionLevelTextsArray[i];
      questionLevelInput.type = 'radio';
      questionLevelInput.name = 'questionLevel';
      questionLevelInput.classList = `questionRadioButtons`;

      questionLevelRadioButtons.push(questionLevelInput);
      containerDiv.appendChild(questionLevelTexts);
      questionLevelTexts.appendChild(questionLevelInput);
    }

    containerDiv.appendChild(submitButton);
   
    

    submitButton.addEventListener("click", () => {

      let questionLevelButtons = document.querySelectorAll(".questionRadioButtons")
    
      let levelText;
      questionLevelButtons.forEach(function(el,indx){
        if(el.checked == true) {
          levelText = questionLevelTextsArray[indx];
          console.log(levelText)
        }
      })

      console.log(questionArea.value)
      radioButtons.forEach(function(el) {   
       console.log(el.checked);
      })

      let newQuestion = {
        question: `${questionArea.value}`,
        answers: [
          { text: `${answerTextAreaOptions[0].value}`, correct: `${radioButtons[0].checked}`},
          { text: `${answerTextAreaOptions[1].value}`, correct: `${radioButtons[1].checked}`},
          { text: `${answerTextAreaOptions[2].value}`, correct: `${radioButtons[2].checked}`},
          { text: `${answerTextAreaOptions[3].value}`, correct: `${radioButtons[3].checked}`}
        ], 
        level: levelText
      };

      questions.push(newQuestion);

      console.log(newQuestion)

      containerDiv.classList.add("hidden");
      showAdminPanelQuestions();
    })
 
}


adminGoBackButton.addEventListener("click", () => adminGoBackFunction() )

function adminGoBackFunction() {
  appElement.style.display = "block";
  easyQuestionsElement.style.display = "none";
  adminGoBackButton.classList.add("hidden");

  while (easyQuestionsElement.firstChild) {
    easyQuestionsElement.removeChild(easyQuestionsElement.firstChild);
}
   startQuiz();
}

function showAdminPanelQuestions() {

    if(easyQuestionsElement.style.display == "block") return;

    while (easyQuestionsElement.firstChild) {
      easyQuestionsElement.removeChild(easyQuestionsElement.firstChild);
  }

    adminGoBackButton.classList.remove("hidden");
    adminAddQuestionButton.classList.remove("hidden");
    searchQuestion.classList.remove("hidden");
    adminGoBackButton.enabled = true;

      easyQuestionsElement.style.display = "block";

    for(let currentQuestionIndex = 0; currentQuestionIndex < questions.length; currentQuestionIndex++) {
        const questionDiv = document.createElement("div");     
        const questionItemsDiv = document.createElement("div");   
        const editButton = document.createElement("button");     
        const deleteButton = document.createElement("button");     
        const questionTitle = document.createElement("h1");
        const questionNumber = currentQuestionIndex + 1;
       
        questionDiv.classList.add(`question${currentQuestionIndex}`);

        questionDiv.appendChild(questionItemsDiv);
        questionItemsDiv.classList.add(`question${currentQuestionIndex}-items`)
    
        editButton.innerHTML = "Soruyu Düzenle";
        deleteButton.innerHTML = "Soruyu Sil";
        questionTitle.innerHTML = `Soru ${questionNumber}. ${questions[currentQuestionIndex].question} `

        easyQuestionsElement.appendChild(questionDiv);
        
        questionItemsDiv.appendChild(questionTitle);

        questions[currentQuestionIndex].answers.forEach(function(el,indx) {

            const answerItems = document.createElement("button")        
            answerItems.innerHTML = el.text;

            questionItemsDiv.appendChild(answerItems);
            answerItems.classList.add("btn");
            answerItems.classList.add("btn-answers");
            answerItems.classList.add(`btn-answers${currentQuestionIndex}`);
          })

        questionDiv.appendChild(editButton);
        questionDiv.appendChild(deleteButton);
       // btn set

        editButton.addEventListener("click", () => editQuestion(currentQuestionIndex, questionDiv, questionTitle, editButton, deleteButton));
        deleteButton.addEventListener("click", () => deleteQuestion(currentQuestionIndex,questionDiv, questionTitle, editButton, deleteButton));

       // CSS AREA
       
       deleteButton.classList.add("btn-edit");
       editButton.classList.add("btn-edit");
    }
}


function editQuestion(qIndex,qDiv,question,editbtn,deletebtn) {

  let answersSelector = qDiv.querySelectorAll(".btn-answers");
  let selectCorrectAnswer;
  let questionLevelInput;
  let questionLevelTextsArray;

   questionLevelTextsArray = ["Kolay", "Orta", "Zor"];

    const editAreaDiv = document.createElement(`div${qIndex}`);
    const textArea = document.createElement("textarea");
    const okButton = document.createElement("button");

 
    
    textArea.value = question.innerHTML;
    textArea.classList.add("text-area");
    textArea.classList.add(`text-area-question-title${qIndex}`);

    okButton.innerHTML = "Soruyu Kaydet";
    okButton.classList.add("btn-edit");
    okButton.classList.add(`btn-save-${qIndex}`);

    question.style.display = "none";

    // css area
    editAreaDiv.appendChild(textArea)

    answersSelector.forEach((button, indx) => {
       let siklar = ["A","B","C","D","E","F","G"];

        const answerTextArea = document.createElement("textarea");
        const sikAdi = document.createElement("h3");
        sikAdi.classList.add("h3");
        sikAdi.innerHTML = ` Doğru Cevabı Seç`
         selectCorrectAnswer = document.createElement("input");
         
         

        selectCorrectAnswer.type = 'radio';
        selectCorrectAnswer.name = 'myRadioGroup';
        selectCorrectAnswer.value = `correctOption${indx}`;
        selectCorrectAnswer.id = `${indx}`;
        selectCorrectAnswer.classList = `radioButtons${qIndex}`;

        if (questions[qIndex].answers[indx].correct) {
          selectCorrectAnswer.checked = true;
      }
    
        answerTextArea.value = button.innerHTML;

        answerTextArea.classList.add("text-area");
        answerTextArea.classList.add(`text-area-answers-question${qIndex}`);
        editAreaDiv.appendChild(sikAdi);
        editAreaDiv.appendChild(answerTextArea);
       
        
        sikAdi.appendChild(selectCorrectAnswer);
        button.style.display = "none";
        answerTextArea.classList.add("custom-textarea-answers");
    });

    for(let i = 0; i < 3; i++) {
      questionLevelInput = document.createElement("input");
      questionLevelTexts = document.createElement("h3");
      questionLevelTexts.classList.add("h3");
      questionLevelTexts.innerHTML = questionLevelTextsArray[i];
      questionLevelInput.type = 'radio';
      questionLevelInput.name = 'questionLevel';
      questionLevelInput.classList = `questionRadioButtons${qIndex}`;
      editAreaDiv.appendChild(questionLevelTexts);
      questionLevelTexts.appendChild(questionLevelInput);
    }
    
     
    editAreaDiv.appendChild(okButton);

    textArea.classList.add("custom-textarea-question");

    okButton.addEventListener("click", () => saveFunction() );

    function saveFunction() {

      let radio = document.querySelectorAll(`.radioButtons${qIndex}`);
      let questionRadio = document.querySelectorAll(`.questionRadioButtons${qIndex}`);
       

      questionRadio.forEach(function(el,indx) {
        if(el.checked == true) {
          questions[qIndex].level = `${questionLevelTextsArray[indx]}`;
          console.log(questions[qIndex].level)
        }
      })

      radio.forEach(function(el,indx) {
        if(el.checked == true) {
          questions[qIndex].answers.forEach((answer, i) => {

            answer.correct = false;
        });
        questions[qIndex].answers[indx].correct = true;
         
        }
        el.remove();
      })
      
        if(textArea.value.includes("Soru")) {
            question.innerHTML = `${textArea.value.toString()}`;
            questions[qIndex].question.text = textArea.value;
        } else {
            question.innerHTML = `Soru ${qIndex + 1} . ${textArea.value.toString()}`;
            questions[qIndex].question = textArea.value;
        }
      
        let answersAreaSelector = qDiv.querySelectorAll(".custom-textarea-answers");

        answersSelector.forEach(function(button, index) {
            button.style.display = "block";
            question.style.display = "block";
            
            if (answersAreaSelector[index]) {
                button.innerHTML = answersAreaSelector[index].value;                   
                questions[qIndex].answers[index].text = answersAreaSelector[index].value;
            }          
        })

        answersAreaSelector.forEach(button => {
            button.style.display = "none";
            
        });
        okButton.style.display = "none";
        textArea.style.display = "none";
        const allOptions = document.querySelectorAll(".h3")
       allOptions.forEach((el) => {
        el.style.display = "none";
       })
       
       questionRadio.forEach((el) => {
        el.style.display = "none";
       })
       
        
    }
   
    qDiv.appendChild(editAreaDiv);
}

function deleteQuestion(qIndex,qDiv,question,editbtn, deletebtn) {
  qDiv.style.display = "none";
  editbtn.style.display = "none";
  deletebtn.style.display = "none";
}

function hideQuestions() {
    appElement.style.display = "none";

    showAdminPanelQuestions();
}

adminPanelElement.addEventListener("click" , hideQuestions)

startQuiz();