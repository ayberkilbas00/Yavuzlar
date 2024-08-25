let questions = [
    {
      question: "Türkiyenin başkenti neresidir?",
      answers: [
        { text: "Ankara", correct: true },
        { text: "İstanbul", correct: false },
        { text: "İzmir", correct: false },
        { text: "Bursa", correct: false }
      ]
    },
    {
      question: "En büyük gezegen hangisidir?",
      answers: [
        { text: "Mars", correct: false },
        { text: "Venüs", correct: false },
        { text: "Jüpiter", correct: true },
        { text: "Dünya", correct: false },
        { text: "Uranüs", correct: false }
      ]
    },
    {
      question: "En hızlı kara hayvanı nedir?",
      answers: [
        { text: "Aslan", correct: false },
        { text: "Çita", correct: true },
        { text: "Zebra", correct: false },
        { text: "Geyik", correct: false }
      ]
    },
    {
      question: "Hangi gezegen Dünya'nın uydusudur?",
      answers: [
        { text: "Venüs", correct: false },
        { text: "Mars", correct: false },
        { text: "Ay", correct: true },
        { text: "Merkür", correct: false }
      ]
    },
    {
      question: "Türkiye'nin en uzun nehri hangisidir?",
      answers: [
        { text: "Fırat", correct: true },
        { text: "Dicle", correct: false },
        { text: "Kızılırmak", correct: false },
        { text: "Sakarya", correct: false }
      ]
    },
    {
      question: "En küçük gezegen hangisidir?",
      answers: [
        { text: "Neptün", correct: false },
        { text: "Uranüs", correct: false },
        { text: "Merkür", correct: true },
        { text: "Satürn", correct: false }
      ]
    },
    {
      question: "Hangi ülkenin bayrağında bir yıldız ve hilal bulunur?",
      answers: [
        { text: "İtalya", correct: false },
        { text: "Türkiye", correct: true },
        { text: "Fransa", correct: false },
        { text: "Almanya", correct: false }
      ]
    },
    {
      question: "Dünyanın en yüksek zirvesi hangisidir?",
      answers: [
        { text: "Kilimanjaro", correct: false },
        { text: "Everest", correct: true },
        { text: "McKinley", correct: false },
        { text: "Elbrus", correct: false }
      ]
    },
    {
      question: "Bir yıl kaç gündür?",
      answers: [
        { text: "364", correct: false },
        { text: "365", correct: true },
        { text: "366", correct: false },
        { text: "360", correct: false }
      ]
    },
    {
      question: "Bir haftada kaç gün vardır?",
      answers: [
        { text: "5", correct: false },
        { text: "6", correct: false },
        { text: "7", correct: true },
        { text: "8", correct: false }
      ]
    }
  ];

const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");
const adminPanelElement = document.querySelector("#admin-panel");
const appElement = document.querySelector(".app");
const easyQuestionsElement = document.querySelector(".easy-questions");
const adminGoBackButton = document.querySelector(".admin-go-back-button");

let currentQuestionIndex = 0;
let score = 0;
let userQuestionLevelSelection;

function startQuiz () {
    currentQuestionIndex = 0;
    score = 0;
    nextButton.innerHTML = "Next";
    showQuestion();
    easyQuestionsElement.style.display = "none";
    adminGoBackButton.enabled = false;
}

function showQuestion() {
   resetState();
  let currentQuestion = questions[currentQuestionIndex];
  let questionNo = currentQuestionIndex + 1;
  questionElement.innerHTML = `${questionNo} . ${currentQuestion.question}`;

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

adminGoBackButton.addEventListener("click", () => adminGoBackFunction())


function adminGoBackFunction() {
  appElement.style.display = "block";
  easyQuestionsElement.style.display = "none";
  adminGoBackButton.classList.add("hidden");
  //appElement.remove();
   startQuiz();

                    console.log(questions[0].answers[0].text)
                    console.log(questions[0].answers[1].text)
                    console.log(questions[0].answers[2].text)
                    console.log(questions[0].answers[3].text)
 
}

function showAdminPanelQuestions() {
    if(easyQuestionsElement.style.display == "block") return;

    adminGoBackButton.classList.remove("hidden");
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

let editArrays = [];

function editAlreadyCreatedQuestion(qIndex,qDiv,question,editbtn, deletebtn) {
   const questionItems = document.querySelector(`.question${qIndex}-items`);
   const answerAreaItems = document.querySelectorAll(`.text-area-answers-question${qIndex}`);
   const questionTitle = document.querySelector(`.text-area-question-title${qIndex}`);
   const questionSaveButton =  document.querySelector(`.btn-save-${qIndex}`)
   const answersSelector= document.querySelectorAll(`.btn-answers${currentQuestionIndex}`)
   questionItems.style.display = "none";
   
   answerAreaItems.forEach(function(el) {
    el.style.display = "block";
   })
   questionTitle.style.display = "block";  
   questionSaveButton.style.display = "block";

   questionSaveButton.addEventListener("click", () => saveFunction());

   function saveFunction() {
    if(questionTitle.value.includes("Soru")) {
        question.innerHTML = `${questionTitle.value.toString()}`;
    } else {
        question.innerHTML = `Soru ${qIndex + 1} . ${questionTitle.value.toString()}`;
    }
    
    let answersAreaSelector = answerAreaItems;

    answersSelector.forEach(function(button, index) {
        button.style.display = "block";
        question.style.display = "block";
        questionItems.style.display = "block"
        
        if (answersAreaSelector[index]) {
            button.innerHTML = answersAreaSelector[index].value;          
        }          
    })

    answersAreaSelector.forEach(button => {
        button.style.display = "none";
        
    });
  }
}

function editQuestion(qIndex,qDiv,question,editbtn, deletebtn) {

  let answersSelector = qDiv.querySelectorAll(".btn-answers")

  if(editArrays.includes(qIndex)) {
   editAlreadyCreatedQuestion(qIndex,qDiv,question,editbtn, deletebtn);
   return;
  }
    const editAreaDiv = document.createElement(`div${qIndex}`)
    const textArea = document.createElement("textarea")
    const okButton = document.createElement("button")
    

    textArea.value = question.innerHTML;
    textArea.classList.add("text-area")
    textArea.classList.add(`text-area-question-title${qIndex}`)

    okButton.innerHTML = "Soruyu Kaydet"
    okButton.classList.add("btn-edit");
    okButton.classList.add(`btn-save-${qIndex}`);

    question.style.display = "none";

    // css area
    editAreaDiv.appendChild(textArea)

    answersSelector.forEach((button, indx) => {
        const answerTextArea = document.createElement("textarea");
        let selectCorrectAnswer = document.createElement("input");

        selectCorrectAnswer.type = 'radio';
        selectCorrectAnswer.name = 'myRadioGroup';
        selectCorrectAnswer.value = `correctOption${indx}`
        selectCorrectAnswer.id = `${indx}`;
        selectCorrectAnswer.classList = `radioButtons${qIndex}`;

        answerTextArea.value = button.innerHTML;

        answerTextArea.classList.add("text-area")
        answerTextArea.classList.add(`text-area-answers-question${qIndex}`);

        editAreaDiv.appendChild(answerTextArea);
        editAreaDiv.appendChild(selectCorrectAnswer)
        button.style.display = "none";
        answerTextArea.classList.add("custom-textarea-answers");
    });
     
    editAreaDiv.appendChild(okButton);

    textArea.classList.add("custom-textarea-question");

    okButton.addEventListener("click", () => saveFunction() );


    function saveFunction() {


      const radio = document.querySelectorAll(`.radioButtons${qIndex}`);

      radio.forEach(function(el,indx) {
        console.log(el.checked)
      })
      
        if(textArea.value.includes("Soru")) {
            question.innerHTML = `${textArea.value.toString()}`;
        } else {
            question.innerHTML = `Soru ${qIndex + 1} . ${textArea.value.toString()}`;
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
    }
   
    qDiv.appendChild(editAreaDiv);

    editArrays.push(qIndex);
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
