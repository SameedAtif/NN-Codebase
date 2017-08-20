var Question = function (subj) {
	this.subject = subj;
	this.hasReview = false;
};

var NET_Model = {
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

		cards.each(function (index, item) {
			that.allQuestions[index] = new Question( $(item).find(".curr-section").html() );
		});
		this.activeQuestions = this.allQuestions;

		console.log("NET_Model initialized!");
	},
	userChoices: []
};

var MainController = {
	intervalID: null,
	init: function (totalTime) {
		NET_CardsView.init();
		NET_Model.init();
		NET_HUDView.init();
		NET_CardsView.displayCurrentCard();
		this.startTimer(totalTime);

		console.log("MainController initialized!");
	},
	getCards: function () {
		return NET_CardsView.allCards;
	},
	getAllQuestions: function () {
		return NET_Model.allQuestions;
	},
	getAllActiveQuestions: function () {
		return NET_Model.activeQuestions;
	},
	getTotalQuestions: function () {
		return NET_Model.activeQuestions.length;
	},
	getUserChoices: function () {
		return NET_Model.userChoices;
	},
	setUserChoice: function (index, value) {
		NET_Model.userChoices[index] = value; // value should range from 0 - 3 (total choices are 4)
	},
	setActiveQuestions: function (newQuestionsArray) {
		NET_Model.activeQuestions = newQuestionsArray;
	},
	toggleReview: function (index) {
		NET_Model.allQuestions[index].hasReview = !NET_Model.allQuestions[index].hasReview;
	},
	startTimer: function (totalTime) {
		NET_Model.totalTime = totalTime;
		NET_Model.timeRemaining = totalTime;
		var that = this;

		if (totalTime == -1) { // -1 for no time limit
			NET_HUDView.updateTime("Infinite Time");
		} else {
			NET_HUDView.updateTime(NET_Model.totalTime);
			that.intervalID = setInterval(function () {
				NET_Model.timeRemaining--;
				if (NET_Model.timeRemaining <= 0) {
					// end session
					console.log("Time out");
					clearInterval(that.intervalID);
					that.displayResult();
				} 
				NET_HUDView.updateTime(NET_Model.timeRemaining);
			}, 60000);
		}
		console.log("Timer started!");
	},
	confirmSubmission: function () {
		$(NET_HUDView.confirmationBox).show();
		$(NET_HUDView.confirmationBox).find(".true").click(function () {
			MainController.displayResult();
			$(NET_HUDView.confirmationBox).hide();
			$(NET_HUDView.submitBtn).html("Submitting...").attr("disabled", true);
		});
		$(NET_HUDView.confirmationBox).find(".false").click(function () {
			$(NET_HUDView.confirmationBox).hide();
		});
	},
	displayResult: function () {
		var audio = new Audio('https://www.freesound.org/data/previews/171/171670_2437358-lq.mp3'); // Success sound

		$.get("otests.php/", {
				test_name: NET_Model.testName,
				source_index: NET_Model.sourceIndex,
				user_choices: JSON.stringify(NET_Model.userChoices),
				result: true,
				subject_list: NET_Model.subjects,
				total: NET_Model.allQuestions.length,
				time_taken: (NET_Model.totalTime - NET_Model.timeRemaining)
			}, function (data) {
			if (data[0] != "<") { // '<' would mean it's an HTML tag
				window.location = data; // in this case, html is the URL
				return 0;
			}

			var resultPage = document.createElement("main").innerHTML = data;
			
			document.getElementsByTagName("main")[0].innerHTML = "";
			document.getElementsByTagName("main")[0].innerHTML = resultPage;

			// end session
			console.log("Session ended by user");
			clearInterval(this.intervalID);
			
			audio.play(); // Play success sound
		})
		.fail(function (msg) {
			console.error("Something went wrong! " + msg);
		});
	}
};

var NET_CardsView = {
	allCards: [],
	currentCard: 0,
	init: function () {
		this.loadCards();

		// On clicking the full option box, the option gets selected
		$(".answer .option").on("click", function () {
			$(".card.active .answer .option").removeClass("selected");
			$(this).addClass("selected");
			$(this).children()[0].checked = true;
		});

		console.log("NET_CardsView initialized!");
	},
	loadCards: function () {
		this.allCards = $(".card");
	},
	displayCurrentCard: function () {
		this.allCards.removeClass("active").hide();
		var index = MainController.getAllQuestions().indexOf(MainController.getAllActiveQuestions()[NET_CardsView.currentCard]);
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
			NET_CardsView.currentCard = 0;
			MainController.setActiveQuestions(MainController.getAllQuestions());
			NET_CardsView.displayCurrentCard();
		} else if (this.value == "ans") { // For all answered questions
			var filtered = [],
				userChoices = MainController.getUserChoices();
			MainController.getAllQuestions().forEach(function (item, index) {
				if (userChoices[index] != undefined)
					filtered.push(item);
			});
			NET_CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			NET_CardsView.displayCurrentCard();
		} else if (this.value == "unans") { // For all unanswered questions
			var filtered = [],
				userChoices = MainController.getUserChoices();
			MainController.getAllQuestions().forEach(function (item, index) {
				if (userChoices[index] == undefined)
					filtered.push(item);
			});
			NET_CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			NET_CardsView.displayCurrentCard();
		} else if (this.value == "rev") { // For all questions set for review
			var filtered = [];
			MainController.getAllQuestions().forEach(function (item, index) {
				if (item.hasReview)
					filtered.push(item);
			});
			NET_CardsView.currentCard = 0;
			MainController.setActiveQuestions(filtered);
			NET_CardsView.displayCurrentCard();
		} else {
			console.warn("Unidentified value for Category filter!");
		}
		
	}
};

var NET_HUDView = {
	currentSection: document.querySelector("#curr-section"),
	nthQuestion: document.querySelector("#nth-question"),

	remainingTime: document.querySelector("#timer > #time"),
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
		$(this.categorySelector).on("input change", NET_CardsView.categoryFilter);// TODO: this is being called twice (tested in Chrome), as input AND then change

		$(this.saveBtn).click(function () {
			NET_CardsView.save();
		});
		$(this.nextBtn).click(function () {
			NET_CardsView.nextCard();
		});
		$(this.prevBtn).click(function () {
			NET_CardsView.prevCard();
		});
		$(this.reviewBtn).click(function () {
			MainController.toggleReview(NET_CardsView.currentCard);
		});
		$(this.nextSectionBtn).click(function () {
			NET_CardsView.nextSection();
		});
		$(this.prevSectionBtn).click(function () {
			NET_CardsView.prevSection();
		});
		$(this.firstBtn).click(function () {
			NET_CardsView.jumpToCard(0);
		});
		$(this.lastBtn).click(function () {
			NET_CardsView.jumpToCard(MainController.getTotalQuestions() - 1);
		});
		$(this.submitBtn).click(function () {
			MainController.confirmSubmission();
		});
		
		console.log("NET_HUDView initialized!");
	},
	updateTime: function (newTime) {
		this.remainingTime.innerHTML = newTime;
	}
};