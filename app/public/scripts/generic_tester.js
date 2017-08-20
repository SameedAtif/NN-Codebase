/*
 *
 *    Copyright. All rights reserved, M. Mustaghees Butt.
 *    
 *    Generic tests are always timed, usually 40 minutes.
 *
 *    This code uses MVC.
 */

var Question = function (subj) {
	this.subject = subj;
	this.hasReview = false;
};

var Model = {
	sourceIndex: -1,
	subjects: [],
	testName: "",
	totalQuestions: -1,
	totalTime: null,
	timeRemaining: null,

	allQuestions: [],
	activeQuestions: [],
	init: function () {
		var cards = MainController.getCards(),
			that = this;
		this.subjects = document.querySelector("#meta #subj-list").innerHTML.split(",", 5);
		this.testName = document.querySelector("#meta #test-name").innerHTML;
		this.totalQuestions = cards.length;
		this.userChoices = new Array(this.totalQuestions);
		this.sourceIndex = document.querySelector("#meta #source").innerHTML;

		cards.each(function (index, item) { // for multi-subject tests
			that.allQuestions[index] = new Question( $(item).find(".curr-section").html() );
		});
		this.activeQuestions = this.allQuestions;

		console.log("Model initialized!");
	},
	userChoices: []
};

var MainController = {
	intervalID: null,
	init: function (totalTime) {
		CardsView.init();
		Model.init();
		HUDView.init();
		CardsView.displayCurrentCard();
		this.startTimer(totalTime);

		console.log("MainController initialized!");
	},
	getCards: function () {
		return CardsView.allCards;
	},
	getAllQuestions: function () {
		return Model.allQuestions;
	},
	getAllActiveQuestions: function () {
		return Model.activeQuestions;
	},
	getTotalQuestions: function () {
		return Model.activeQuestions.length;
	},
	getUserChoices: function () {
		return Model.userChoices;
	},
	setUserChoice: function (index, value) {
		Model.userChoices[index] = value; // value should range from 0 - 3 (total choices are 4)
	},
	setActiveQuestions: function (newQuestionsArray) {
		Model.activeQuestions = newQuestionsArray;
	},
	toggleReview: function (index) {
		Model.allQuestions[index].hasReview = !Model.allQuestions[index].hasReview;
	},
	countMarkedQuestions: function () { // used for generic tests
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
		var that = this;

		if (totalTime == -1) { // -1 for no time limit
			HUDView.updateTime("Infinite Time");
		} else {
			HUDView.updateTime(Model.totalTime);
			that.intervalID = setInterval(function () {
				Model.timeRemaining--;
				if (Model.timeRemaining <= 0) {
					// end session
					console.log("Time out");
					that.displayResult();
				} 
				HUDView.updateTime(Model.timeRemaining);
			}, 60000);
		}
		console.log("Timer started!");
	},
	confirmSubmission: function () {
		$(HUDView.confirmationBox).show();
		$(HUDView.confirmationBox).find(".true").click(function () {
			MainController.displayResult();
			$(HUDView.confirmationBox).hide();
			$(HUDView.submitBtn).html("Submitting...").attr("disabled", true);
		});
		$(HUDView.confirmationBox).find(".false").click(function () {
			$(HUDView.confirmationBox).hide();
		});
	},
	displayResult: function () {
		var audio = new Audio('https://www.freesound.org/data/previews/171/171670_2437358-lq.mp3'), // Success sound
			that = this;
		$.get("otests.php/", {
				test_name: Model.testName,
				source_index: Model.sourceIndex,
				user_choices: JSON.stringify(Model.userChoices),
				result: true,
				subject_list: Model.subjects,
				total: Model.totalQuestions,
				time_taken: (Model.totalTime - Model.timeRemaining)
			}, function (data) {
			
			var URL = new RegExp("^((https|http)?://)");
			if (URL.test(data)) {
				window.location = data; // in this case, html is the URL
				return 0;
			}

			var resultPage = document.createElement("main").innerHTML = data;
			
			document.getElementsByTagName("main")[0].innerHTML = "";
			document.getElementsByTagName("main")[0].innerHTML = resultPage;
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]); // render dynamically added LaTeX

			// end session
			clearInterval(that.intervalID);
			audio.play(); // Play success sound
		})
		.fail(function (msg) {
			console.error("Something went wrong! " + msg);
		});

		console.log("Session ended by user");
	}
};

var CardsView = {
	allCards: [],
	currentCard: 0,
	init: function () {
		this.loadCards();
		if (HUDView.progress) HUDView.updateProgress(MainController.countMarkedQuestions());
		/*
		 * Direct Handler - On clicking the option, Model will get updated.
		 * On clicking the full option box:
		 * - the option gets selected ('selected' class is added)
		 * - user's choice is saved in the Model
		 * - update progress (number of questions done)
		 */
		var that = this;
		$(".direct .answer .option").click(function (e) {
			var currentCard = $(that.allCards[that.currentCard]);
			
			currentCard.find(".answer .option").removeClass("selected");
			
			$(this).addClass("selected");
			
			// saving user choice in the Model
			var index = that.currentCard;
			var val = $.inArray($(this)[0], currentCard.find(".answer .option")); // option index
			MainController.setUserChoice(index, val);
			
			// updating progress
			var n = MainController.countMarkedQuestions();
			HUDView.updateProgress(n);
		});

		/** Indirect Handler - There is, probably, a separate button which save the option in the Model. **/
		// On clicking the full option box, the option gets selected
		$(".indirect .answer .option").on("click", function () {
			$(".card.active .answer .option").removeClass("selected");
			$(this).addClass("selected");
			$(this).children()[0].checked = true;
		});
		
		console.log("CardsView initialized!");
	},
	loadCards: function () {
		this.allCards = $(".card");
	},
	displayCurrentCard: function () {
		this.allCards.removeClass("active").hide();
		var index = MainController.getAllQuestions().indexOf(MainController.getAllActiveQuestions()[CardsView.currentCard]);
		$(this.allCards[index]).show();
		$(this.allCards[index]).addClass("active");
	},
	save: function () {
		MainController.setUserChoice( this.currentCard, $.inArray($(".card.active .answer input:checked")[0], $(".card.active .answer input")) );
	},
	nextCard: function () {
		if (this.currentCard < MainController.getAllActiveQuestions().length - 1) {
			this.currentCard++;
			this.displayCurrentCard(); // show current card
		} else
			console.warn("Limit reached!");
	},
	prevCard: function () {
		if (this.currentCard > 0) {
			this.currentCard--;
			this.displayCurrentCard();
		} else
			console.warn("Limit reached!");
	},
	nextSection: function () {
		var questions = MainController.getAllActiveQuestions();
		var currSectionSubject = questions[this.currentCard].subject;
		
		for (var i = this.currentCard; i <= questions.length - 1; i++) {
			if (questions[i].subject != currSectionSubject) {
				this.currentCard = i;
				this.displayCurrentCard();
				break;
			}
		}
		
	},
	prevSection: function () {
		var questions = MainController.getAllActiveQuestions(),
			currSectionSubject = questions[this.currentCard].subject,
			that = this;
		
		for (var i = this.currentCard; i >= 0; i--) {
			if (questions[i].subject != currSectionSubject) {
				questions.some(function (item, index) {
					if (item.subject == questions[i].subject) {
						that.currentCard = index;
						that.displayCurrentCard();
						return true;
					}
				});
				break;
			}
		}
	},
	jumpToCard: function (n) {
		this.currentCard = n;
		this.displayCurrentCard();
	},
	categoryFilter: function () {
		// TODO: if there is no currQuestion, then display an error
		if (this.value == "all") {
			CardsView.currentCard = 0;
			MainController.setActiveQuestions(MainController.getAllQuestions());
			CardsView.displayCurrentCard();
		} else if (this.value == "ans") { // For all answered questions
			var filtered = [],
				userChoices = MainController.getUserChoices();
			MainController.getAllQuestions().forEach(function (item, index) {
				if (userChoices[index] != undefined)
					filtered.push(item);
			});
			CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			CardsView.displayCurrentCard();
		} else if (this.value == "unans") { // For all unanswered questions
			var filtered = [],
				userChoices = MainController.getUserChoices();
			MainController.getAllQuestions().forEach(function (item, index) {
				if (userChoices[index] == undefined)
					filtered.push(item);
			});
			CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			CardsView.displayCurrentCard();
		} else if (this.value == "rev") { // For all questions set for review
			var filtered = [];
			MainController.getAllQuestions().forEach(function (item, index) {
				if (item.hasReview)
					filtered.push(item);
			});
			CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			CardsView.displayCurrentCard();
		} else {
			console.warn("Unidentified value for Category filter!");
		}
		
	}
};

var HUDView = {
	currentSection: document.querySelector("#curr-section"),
	nthQuestion: document.querySelector("#nth-question"),

	remainingTime: document.querySelector("#timer > #time"),
	progress: document.querySelector("#progress > span"),
	categorySelector: document.querySelector("#categories > select"),

	saveBtn: document.querySelector(".save-btn"),
	nextBtn: document.querySelector(".next-btn"),
	prevBtn: document.querySelector(".prev-btn"),
	reviewBtn: document.querySelector(".review-btn"),
	nextSectionBtn: document.querySelector(".next-section-btn"),
	prevSectionBtn: document.querySelector(".prev-section-btn"),
	firstBtn: document.querySelector(".first-btn"),
	lastBtn: document.querySelector(".last-btn"),
	helpBtn: document.querySelector(".help-btn"), // TODO
	
	submitBtn: document.querySelector("#final > button"),
	confirmationBox: document.querySelector(".confirmation-box"),
	init: function () {
		$(this.categorySelector).on("input change", CardsView.categoryFilter);// TODO: this is being called twice (tested in Chrome), as input AND then change

		$(this.saveBtn).click(function () {
			CardsView.save();
		});
		$(this.nextBtn).click(function () {
			CardsView.nextCard();
		});
		$(this.prevBtn).click(function () {
			CardsView.prevCard();
		});
		$(this.reviewBtn).click(function () {
			MainController.toggleReview(CardsView.currentCard);
		});
		$(this.nextSectionBtn).click(function () {
			CardsView.nextSection();
		});
		$(this.prevSectionBtn).click(function () {
			CardsView.prevSection();
		});
		$(this.firstBtn).click(function () {
			CardsView.jumpToCard(0);
		});
		$(this.lastBtn).click(function () {
			CardsView.jumpToCard(MainController.getTotalQuestions() - 1);
		});
		$(this.submitBtn).click(function () {
			MainController.confirmSubmission();
		});
		
		console.log("HUDView initialized!");
	},
	updateTime: function (newTime) {
		this.remainingTime.innerHTML = newTime;
	},
	updateProgress: function (n) { // n is the number of questions whose answers have been marked (not necessarily correct)
		this.progress.innerHTML = n;
	}
};

window.onload = function () {
	MainController.init(40);
}