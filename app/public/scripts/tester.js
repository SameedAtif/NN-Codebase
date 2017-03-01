/*
 *
 *    Copyright. All rights reserved, M. Mustaghees Butt.
 *    
 *    GUIDE:
 *    1. We load all raw questions data(JSON) via Ajax. Then we create objects for each question whose subject gets filtered, i.e: if chemistry is not in the subjects of current session then its questions will not be added. This happens in
 *    the initialization function, initialize().
 *
 */

var xhr = new XMLHttpRequest();

function loadQuestions(path) {
	var mcqs;
	$.ajaxSetup({cache: false});
	
	$.getJSON(path, function(data) {
		mcqs = data;
		mcqs.forEach(function (item, index) {
			questionsRaw.push(item);
		});
		
		initialize();
	}).error(function () { console.log("failed") });
	
}

var   subjects = document.getElementById("subjList").innerHTML.split(" ", 5),
		questionsRaw = [],
		Questions = [],
		currQuestions = [],
		currQuestion,
		currQuestionCounter = 0;
		// remove empty string from `subjects` array
		subjects = subjects.filter(function(e){ return e === 0 || e });

var  QuestionsArea = document.getElementById("question"),
		OptionsArea = document.getElementsByClassName("option-text");

// INITIALIZATION
function initialize() {
	var counter = 0;
	questionsRaw.forEach(function (item, index) {
		if ( subjects.indexOf(item.subject) !== -1 ) {
			Questions[counter] = new Question(item.statement, item.options, item.answer, item.subject, item.explanation);
			counter++;
		}
	});
	
	// On clicking the full option box, the option gets selected
	$("#answer .option").on("click", function () {
		$("#answer .option").removeClass("selected");
		$(this).addClass("selected");
		$(this).children()[0].checked = true;
	});
	
	console.log("Initialized");
	currQuestions = Questions;
	currQuestion = currQuestions[currQuestionCounter];
	currQuestion.display();
}

function save() {
	currQuestion.userAnswer = $.inArray($("#answer input:checked")[0], $("#answer input"));
}

function setReview() {
	currQuestion.hasReview = true;
}

function nextQ() {
	if (currQuestionCounter != (currQuestions.length - 1)) {
		currQuestionCounter = currQuestionCounter + 1;
		currQuestion = currQuestions[currQuestionCounter];
		currQuestion.display();
	}
}

function prevQ() {
	if (currQuestionCounter != 0) {
		currQuestionCounter = currQuestionCounter - 1;
		currQuestion = currQuestions[currQuestionCounter];
		currQuestion.display();
	}
}

function nextSection() {
	var  currSectionSubject = currQuestion.subject;
	
	for (var i = currQuestionCounter; i <= currQuestions.length - 1; i++) {
		if (currQuestions[i].subject != currSectionSubject) {
			currQuestion = currQuestions[i];
			currQuestionCounter = currQuestions.indexOf(currQuestion);
			currQuestion.display();
			break;
		}
	}
	
}

function prevSection() {
	var  currSectionSubject = currQuestion.subject;
	
	for (var i = currQuestionCounter; i >= 0; i--) {
		
		if (currQuestions[i].subject != currSectionSubject) {
			currQuestions.some(function (item, index) {
				if (item.subject == currQuestions[i].subject) {
					currQuestion = item;
					currQuestionCounter = currQuestions.indexOf(currQuestion);
					currQuestion.display();
					return true;
				}
			});
			break;
		}
	}
	
}

function firstQ() {
	currQuestionCounter = 0;
	currQuestion = currQuestions[currQuestionCounter];
	currQuestion.display();
}

function lastQ() {
	currQuestionCounter = currQuestions.length - 1;
	currQuestion = currQuestions[currQuestionCounter];
	currQuestion.display();
}

// CATEGORY FILTER
document.querySelector("#categories select").addEventListener("input", catFilter);
document.querySelector("#categories select").addEventListener("change", catFilter);

function catFilter() {
	
	if (this.value == "all") {
		currQuestionCounter = 0;
		currQuestions = Questions;
		currQuestion = Questions[0];
		currQuestion.display();
	}
	// For all answered questions
	else if (this.value == "ans") {
		currQuestions = [];
		Questions.forEach(function (item, index) {
			if (item.userAnswer != undefined) {
				currQuestions.push(item);
			}
		});
		currQuestionCounter = 0;
		currQuestion = currQuestions[0]
		currQuestion.display();
	} else if (this.value == "unans") {
		currQuestions = [];
		Questions.forEach(function (item, index) {
			if (item.userAnswer == undefined) {
				currQuestions.push(item);
			}
		});
		currQuestionCounter = 0;
		currQuestion = currQuestions[0]
		currQuestion.display();
	} else if (this.value == "rev") {
		currQuestions = [];
		Questions.forEach(function (item, index) {
			if (item.hasReview) {
				currQuestions.push(item);
			}
		});
		currQuestionCounter = 0;
		currQuestion = currQuestions[0]
		currQuestion.display();
	}
	// if the value does not match any of above
	else {
		console.log("unidentified value");
	}
	
}

// Questions Object Constructor/Class
var Question = function(stmnt, opts, ans, subj, exp) {
	this.statement = stmnt;
	this.options = opts;
	this.answer = ans;
	this.subject = subj;
	this.hasReview;
	this.userAnswer;
	this.explanation = exp;
	this.display = function () {
		QuestionsArea.children[0].innerHTML = this.statement;
		
		// clearing radio buttons
		$('input[name=answer]').attr('checked', false);
		// filling new options' text
		var userAnswer = this.userAnswer;
		this.options.forEach(function (item, index) {
			OptionsArea[index].innerHTML = item;
			if (index == userAnswer) {
				$(OptionsArea[index].parentNode).addClass("selected");
				$('input[name=answer]')[index].checked = true;
			} else {
				$(OptionsArea[index].parentNode).removeClass("selected");
			}
		});
		
		document.getElementById("currSection").innerHTML = this.subject;
		document.getElementById("nthQuestion").innerHTML = currQuestionCounter+1 + " of " + currQuestions.length;
		
		// For rendering dynamic mathematics written in LaTeX
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	};
}

// calculate results
var  totalMarks,
		marks,
		detailedMarks = {
			math: 0,
			physics: 0,
			chemistry: 0,
			computer: 0,
			english: 0,
			intelligence: 0
		},
		percentage;
function calculateResult() {
	marks = 0;
	totalMarks = Questions.length;
	
	Questions.forEach(function (item, index) {
		if (item.userAnswer == item.answer) {
			marks++;
			detailedMarks[item.subject]++;
		}
	});
	
	percentage = Math.round( (marks/totalMarks) * 100 * 10) / 10; // Math.round(n * 10) / 10 to round to 1 decimal place
}

function displayResult() {
	calculateResult();
	
	$.get("otests.php", {result: true, marks: marks, tt: timeTaken, perc: percentage}, function (data) {
		var resultPage = document.createElement("main");
		resultPage.innerHTML = data;
		
		subjects.forEach(function (item, index) {
			var n = (function () {
				var x = 0;
				Questions.forEach(function (i) {
					(i.subject == item) ? x++ : 0;
				});
				return x;
			}) ();
			
			$(resultPage).find("tbody").append("<tr><td>" + item + "</td>" + "<td>" + n + "</td><td>" + detailedMarks[item] + "</td><td>" + (detailedMarks[item]/n)*100 + "%</td></tr>");
		});
		
		// fill the details - questions, marked answers and explanations
		Questions.forEach(function (item, index) {
			var string = "<div class='question'><div class='statement'>Q." + (index+1) + ": " + item.statement + "</div><div class='options'><ol type='i'>";
			item.options.forEach(function (option, optionIndex) {
				if (optionIndex == item.userAnswer && item.userAnswer == item.answer) {
					string += "<li class='userChoice correct'>" + option + "</li>";
				} else if (optionIndex == item.userAnswer && item.userAnswer != item.answer) {
					string += "<li class='userChoice'>" + option + "</li>";
				} else if (optionIndex == item.answer) {
					string += "<li class='correct'>" + option + "</li>";
				} else {
					string += "<li>" + option + "</li>";
				}
				
			});
			string += "</ol></div><div class='explanation'><h4>Explanation:</h4>" + item.explanation + "</div></div>"
			
			$(resultPage).find("#details").append(string);
		});
		
		document.getElementsByTagName("main")[0].innerHTML = "";
		$("main").append( $(resultPage).find("main") );
	});
	
	// end session
	console.log("Session ended by user");
	clearInterval(intervalID);
	
	// Play audio
	var audio = new Audio('https://www.freesound.org/data/previews/171/171670_2437358-lq.mp3');
	audio.play();
	
}

var timeTaken = 0;
var intervalID;
function timer(time) {
	var initTime = time;
	
	if (initTime == -1) {
		// Infinite Time
		document.getElementById("timer").innerHTML = "Infinite Time";
	} else {
		document.getElementById("time").innerHTML = initTime;
		intervalID = setInterval(function () {
			time--;
			timeTaken++;
			if (time <= 0) {
				// end session
				console.log("Time out");
				clearInterval(intervalID);
				displayResult();
			} 
			document.getElementById("time").innerHTML = time;
		}, 60000);
	}
	
}