<?php

namespace Core\Settings;
require_once 'vendor/autoload.php';

use Core\Router;
use Views\Dashboard\DashboardPage;

/* this is also the entry point for ajax requests, bad idea? */

use Sessions\DashboardSession;

Router::register([

	# ---------- tabs for ajax stuff

	['G', 'tabs', function() { 
		$tabName = $_GET['tab'];

		/*
		 * individual tabs can choose to accept parameters.
		 * this is done WITHIN the tab_*.php file
		 * for example, the status_box takes additional meaning and message
		 * parameters
		 */

		require_once __DIR__ . "/../../Views/Dashboard/crud/tab_$tabName.php";
?>
<?php
	}] ,

	# ---------- post ajax stuff
	# ---------- and then send back the new inner contents of the status_box

	['P', 'post', function() { 
		var_dump($_POST);
?>
<?php
	}] ,

	# ------------------------------

	['G', '', function() { 
		$dashboardSession = new DashboardSession();
		$dashboardSession->setTeam(null);
		$page = new DashboardPage();
		$page->title = 'Teams'; 
		require_once __DIR__ . '/../../Views/Dashboard/crud/all.php';
		echo $page->output(ob_get_clean());
	}] ,

	['G', 'minty', function() { 
		require_once __DIR__ . '/../../Views/Dashboard/crud/minty_ref.php';
	}] ,

	['G', 'home', function() { 
		require_once __DIR__ . '/../../Views/Survey/views/home.php';
	}] ,

	# -- begin side menu

	['G', 'teams', function() {
		$dashboardSession = new DashboardSession();
		$dashboardSession->setTeam(null);
		$page = new DashboardPage();
		$page->title = 'Teams'; 
		require_once __DIR__ . '/../../Views/Dashboard/views/teams.php';
		echo $page->output(ob_get_clean());
	}],

	['GP', 'add_team', function() { 
		$page = new DashboardPage();
		require_once __DIR__ . '/../../Views/Dashboard/views/add_team_2.php';
		$page->output(ob_get_clean());
	}] ,

	['GP', 'add_respondent', function() { 
		$dashboardSession = new DashboardSession();
		$dashboardSession->parseTeam();
		$team_id = $dashboardSession->getTeamId();
		$page = new DashboardPage();
		require_once __DIR__ . '/../../Views/Dashboard/views/add_respondent.php';
		$page->output(ob_get_clean());
	}] ,

	['G', 'team', function() {
		$dashboardSession = new DashboardSession();
		$dashboardSession->parseTeam();
		# to set the respondent as null if applicable
		$dashboardSession->parseRespondent();
		# todo take team parameter e.g ?t_id=1
		$page = new DashboardPage();
		$page->title = 'Team'; 
		require_once __DIR__ . '/../../Views/Dashboard/views/team.php';
		echo $page->output(ob_get_clean());
	}],

	# ---------------------------------------------------------------

	# ind_report
	# cons_ind_report
	# team_report
	# cons_team_report

	['G', 'report', function() { 
		$page = new DashboardPage();
		require_once __DIR__ . '/../../Views/Dashboard/views/report.php';
		$page->output(ob_get_clean());
	}] ,

	['G', 'ind_report', function() { 
		$dashboardSession = new DashboardSession();
		$dashboardSession->setReportType('individual');
		$dashboardSession->parseTeam();
		$dashboardSession->parseRespondent();
		$dashboardSession->parseSurveyRound();
		$page = new DashboardPage();
		require_once __DIR__ . '/../../Views/Dashboard/views/report.php';
		$page->output(ob_get_clean());
	}] ,

	['G', 'team_report', function() { 
		$dashboardSession = new DashboardSession();
		$dashboardSession->setReportType('team');
		$dashboardSession->parseTeam();
		$dashboardSession->parseRespondent();
		$dashboardSession->parseSurveyRound();
		$page = new DashboardPage();
		require_once __DIR__ . '/../../Views/Dashboard/views/report.php';
		$page->output(ob_get_clean());
	}] ,

	# ---------------------------------------------------------------

	['G', 'survey', function() { 
		require_once __DIR__ . '/../../Views/Survey/views/survey.php';
	}] ,

	# -- end side menu

]);

/*
	['G', '', function() { 
	['G', 'home', function() { ['G', 'survey', function() { 
	['G', 'report', function() { 
	['GP', 'add_team', function() { 
	['GP', 'add_respondent', function() { 
	['G', 'teams', function() {
	['G', 'team', function() {
 */
