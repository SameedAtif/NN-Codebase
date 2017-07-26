/*
 *
 *    Copyright. All rights reserved, M. Mustaghees Butt.
 *    
 *    Generic tests are always timed, usually 40 minutes.
 *
 *    This code uses MVC.
 */


var Model = {
	source: "",
	test_name: "",
	totalQuestions: -1,
	totalTime: null,
	timeRemaining: null,
	init: function () {
		this.totalQuestions = MainController.getCards().length;
		this.userChoices = new Array(this.totalQuestions);
		this.source = document.querySelector("#meta #source").innerHTML;
		this.test_name = document.querySelector("#meta #test_name").innerHTML;
		console.log("Model initialized!");
	},
	userChoices: []
};

var MainController = {
	init: function () {
		CardsView.init();
		Model.init();
		HUDView.init();
		this.startTimer(40); // 40 minutes in total
		console.log("MainController initialized!");
	},
	getCards: function () {
		return CardsView.allCards;
	},
	getTotalQuestions: function () {
		return Model.totalQuestions;
	},
	setUserChoice: function (index, value) {
		Model.userChoices[index] = value; // value should range from 0 - 3 (total choices are 4), while index may range from 0 to 39 (since total questions are supposed to be 40)
	},
	countMarkedQuestions: function () {
		var counter = 0;
		var data = Model.userChoices;
		data.forEach(function (item, index) {
			if (item != undefined) {
				counter++;
			}
		});
		return counter;
	},
	startTimer: function (totalTime) {
		Model.totalTime = totalTime;
		Model.timeRemaining = totalTime;
		var intervalID;
		
		HUDView.updateTime(Model.timeRemaining);
		intervalID = setInterval(function () {
			Model.timeRemaining--;
			if (Model.timeRemaining <= 0) {
				// end session
				console.log("Time out");
				clearInterval(intervalID);
				this.displayResult();
			} 
			HUDView.updateTime(Model.timeRemaining);
		}, 60000);
		
		console.log("Timer started!");
	},
	displayResult: function () {
		// getting original data
		var that = this;
		$.get(Model.source, function (data) {
			// perform calculations
			var  totalMarks = that.getTotalQuestions(),
			obtainedMarks = 0,
			timeTaken = Model.totalTime - Model.timeRemaining,
			percentage;
			
			data.forEach(function (item, index) {
				if (item.answer == Model.userChoices[index]) {
					obtainedMarks++;
				}
			});
			
			percentage = Math.round( (obtainedMarks/totalMarks) * 100 * 10) / 10; // Math.round(n * 10) / 10 to round to 1 decimal place
			
			// display result (if user is signed in, they will be redirected)
			$.get("otests.php/", {test_name: Model.test_name, user_choices: Model.userChoices, result: true, subject_list: null, marks: obtainedMarks, total: Model.totalQuestions, time_taken: timeTaken, perc: percentage}, function (html) {
				console.log(html);
				if (html == "inserted")
					return 0; // redirect to test detail in user's profile
				var resultPage = document.createElement("main");
				resultPage.innerHTML = html;
				
				// remove/empty summary section
				$(resultPage).find("section")[1].innerHTML = "";
				
				// fill the details - questions, marked answers and explanations
				data.forEach(function (item, index) {
					var string = "<div class='question'><div class='statement'>Q." + (index+1) + ": " + item.statement + "</div><div class='options'><ol type='i'>";
					item.options.forEach(function (option, optionIndex) {
						if (optionIndex == Model.userChoices[index] && Model.userChoices[index] == item.answer) {
							string += "<li class='userChoice correct'>" + option + "</li>";
						} else if (optionIndex == Model.userChoices[index] && Model.userChoices[index] != item.answer) {
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
			// clearInterval(intervalID);
			
			// Play audio
			var audio = new Audio('https://www.freesound.org/data/previews/171/171670_2437358-lq.mp3');
			audio.play();
		});
	}
};

var CardsView = {
	allCards: [],
	currentCard: 0,
	init: function () {
		this.loadCards();
		this.allCards.hide();
		this.displayCard();
		
		/*
		 * On clicking the full option box,
		 * - the option gets selected ('selected' class is added)
		 * - user's choice is saved in the Model
		 * - update progress (number of questions done)
		 */
		var that = this;
		$(".answer .option").click(function (e) {
			var currentCard = $(that.allCards[that.currentCard]);
			
			currentCard.find(".answer .option").removeClass("selected");
			
			$(this).addClass("selected");
			
			// saving user choice in the Model
			var index = that.currentCard;
			var val = $.inArray($(this)[0], currentCard.find(".answer .option")); // value
			MainController.setUserChoice(index, val);
			
			// updating progress
			var n = MainController.countMarkedQuestions();
			HUDView.updateProgress(n);
		});
		
		console.log("CardsView initialized!");
	},
	loadCards: function () {
		this.allCards = $(".card");
	},
	displayCard: function () {
		$(this.allCards[this.currentCard]).show();
	},
	nextCard: function () {
		if (this.currentCard < this.allCards.length - 1) {
			$(this.allCards[this.currentCard]).hide(); // hide current card
			this.currentCard++;
			this.displayCard(); // show current slide
		} else {
			console.warn("Limit reached!");
		}
	},
	prevCard: function () {
		if (this.currentCard > 0) {
			$(this.allCards[this.currentCard]).hide(); // hide current card
			this.currentCard--;
			this.displayCard(); // show current slide
		} else {
			console.warn("Limit reached!");
		}
	},
	jumpToCard: function (n) {
		$(this.allCards[this.currentCard]).hide(); // hide current card
		this.currentCard = n;
		this.displayCard(); // show current slide
	}
};

var HUDView = {
	progress: document.querySelector("#progress > span"),
	remainingTime: document.querySelector("#timer #time"),
	nextBtn: document.querySelector(".next-btn"),
	prevBtn: document.querySelector(".prev-btn"),
	firstBtn: document.querySelector(".first-btn"),
	lastBtn: document.querySelector(".last-btn"),
	submitBtn: document.querySelector("#final > button"),
	init: function () {
		$(this.nextBtn).click(function () {
			CardsView.nextCard();
		});
		$(this.prevBtn).click(function () {
			CardsView.prevCard();
		});
		$(this.firstBtn).click(function () {
			CardsView.jumpToCard(0);
		});
		$(this.lastBtn).click(function () {
			CardsView.jumpToCard(MainController.getTotalQuestions() - 1);
		});
		$(this.submitBtn).click(function () {
			MainController.displayResult();
		});
		
		this.updateProgress(0);
		console.log("HUDView initialized!");
	},
	updateTime: function (newTime) {
		this.remainingTime.innerHTML = newTime;
	},
	updateProgress: function (n) { // n is the number of questions whose answers have been marked (not necessarily correct)
		this.progress.innerHTML = n;
	}
};

// For rendering dynamic mathematics written in LaTeX
//MathJax.Hub.Queue(["Typeset",MathJax.Hub]);