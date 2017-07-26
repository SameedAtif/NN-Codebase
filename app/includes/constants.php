<?php
	/**
	 * Global Constants
	 *
	 */

	/** Meta **/
	define("SITE_PROTOCOL", "http");
	define("SITE_DOMAIN", "notesnetwork.org");
	define("SITE_BASE_LINK", "/app/public/");

	// your database's name
	define("DATABASE", "test");

	// your database's username
	define("DB_USERNAME", "root");

	// your database's password
	define("DB_PASSWORD", "");

	// your database's server
	define("SERVER", "localhost");

	/** Mail constants **/
	define("SMTP_HOST", ""); // main and backup SMTP servers
	define("SMTP_USERNAME", "");
	define("SMTP_PASSWORD", "");

	/** FB Login **/
	define("FB_CLIENT_ID", "");
	define("FB_CLIENT_SECRET", "");

	/**
	 * MODELS
	 *
	 */
	$TestsModel = [
		"NET" => [
			"title" => "NET (NUST Entry Test) Mockup Exam",
			"desc" => "Online mockup examination of NET (NUST Entry Test).",
			"path_home" => "online_tests/NET/home",
			"path_test" => "online_tests/NET/test",
			"test_data" => ["path_data" => ["",
											"https://raw.githubusercontent.com/MustagheesButt/Online-tests/master/NET/set2.json",
											""]] // whatever is to be passed while rendering test template
		],
		"SAT" => [
			"title" => "SAT (Scholastic Aptitude Test) Practice",
			"desc" => "Online mockup examination of SAT (Scholastic Aptitude Test) in co-operation with Khanacademy.",
			"path_home" => "online_tests/SAT/home",
			"path_test" => null,
			"test_data" => []
		],
		"general_knowledge" => [
			"title" => "General Knowledge Test",
			"desc" => "Test you general knowledge with our specially crafted quiz to benchmark your knowledge and memory.",
			"path_home" => "online_tests/Generic/home",
			"path_test" => "online_tests/Generic/test",
			"test_data" => ["test" => "general_knowledge", "test_title" => "General Knowledge", "path_data" => "./data/otests/general_knowledge.json"]
		],
		"computer_science" => [
			"title" => "Computer Science Test",
			"desc" => "Test you Computer Science knowledge with our specially crafted quiz to benchmark your knowledge and memory.",
			"path_home" => "online_tests/Generic/home",
			"path_test" => "online_tests/Generic/test",
			"test_data" => ["test" => "computer_science", "test_title" => "Computer Science", "path_data" => "./data/otests/computer_science.json"]
		]
	];
?>