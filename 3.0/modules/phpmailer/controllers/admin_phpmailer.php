<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */

class Admin_PHPMailer_Controller extends Admin_Controller {
  public function index() {
    // Generate a new admin page.
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_phpmailer.html");
    $view->content->phpmailer_form = $this->_get_admin_form();
    print $view;
  }

  public function saveprefs() {
    // Prevent Cross Site Request Forgery
    access::verify_csrf();

    // Figure out the values of the text boxes    
    $str_phpmailer_path = Input::instance()->post("phpmailer_path");
    $str_phpmailer_from_addr = Input::instance()->post("phpmailer_from_address");
    $str_phpmailer_from_name = Input::instance()->post("phpmailer_from_name");    
    $str_smtp_server = Input::instance()->post("phpmailer_smtp_server");
    $str_use_ssl = Input::instance()->post("phpmailer_use_ssl");
    $str_use_tls = Input::instance()->post("phpmailer_use_tls");
	$str_smtp_login = Input::instance()->post("phpmailer_smtp_login");
    $str_smtp_pass = Input::instance()->post("phpmailer_smtp_password");
    $str_smtp_port = Input::instance()->post("phpmailer_smtp_port");

    if (count($str_use_ssl) > 0) {
      $str_use_ssl = true;
    } else {
      $str_use_ssl = false;
    }

    if (count($str_use_tls) > 0) {
      $str_use_tls = true;
    } else {
      $str_use_tls = false;
    }

    // Save Settings.
    module::set_var("phpmailer", "phpmailer_path", $str_phpmailer_path);
    module::set_var("phpmailer", "phpmailer_from_address", $str_phpmailer_from_addr);
    module::set_var("phpmailer", "phpmailer_from_name", $str_phpmailer_from_name);
    module::set_var("phpmailer", "smtp_server", $str_smtp_server);
    module::set_var("phpmailer", "use_ssl", $str_use_ssl);
    module::set_var("phpmailer", "use_tls", $str_use_tls);
	module::set_var("phpmailer", "smtp_login", $str_smtp_login);
    module::set_var("phpmailer", "smtp_password", $str_smtp_pass);
    module::set_var("phpmailer", "smtp_port", $str_smtp_port);
    message::success(t("Your Settings Have Been Saved."));

    // Load Admin page.
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_phpmailer.html");
    $view->content->phpmailer_form = $this->_get_admin_form();
    print $view;
  }

  private function _get_admin_form() {
    // Make a new Form.
    $form = new Forge("admin/phpmailer/saveprefs", "", "post",
                      array("id" => "g-php-mailer-admin-form"));
                      
    // Create the input boxes for the PHPMailer Settings                      
    $phpmailerGroup = $form->group("PHPMailerSettings");
    $phpmailerGroup->input("phpmailer_path")
                   ->label(t("Location of PHPMailer Class"))
                   ->value(module::get_var("phpmailer", "phpmailer_path"));
    $phpmailerGroup->input("phpmailer_from_address")
                   ->label(t("From Email Address"))
                   ->value(module::get_var("phpmailer", "phpmailer_from_address"));
    $phpmailerGroup->input("phpmailer_from_name")
                   ->label(t("From Name"))
                   ->value(module::get_var("phpmailer", "phpmailer_from_name"));

    // Create the input boxes for the SMTP server settings
    $phpmailerSMTP = $form->group("PHPMailerSMTPSettings");
    $phpmailerSMTP->input("phpmailer_smtp_server")
                   ->label(t("SMTP Server Address"))
                   ->value(module::get_var("phpmailer", "smtp_server"));
    $phpmailerSMTP->input("phpmailer_smtp_login")
                   ->label(t("SMTP Login Name"))
                   ->value(module::get_var("phpmailer", "smtp_login"));
    $phpmailerSMTP->password("phpmailer_smtp_password")
                   ->label(t("SMTP Password"))
                   ->value(module::get_var("phpmailer", "smtp_password"));
    $phpmailerSMTP->input("phpmailer_smtp_port")
                   ->label(t("SMTP Port"))
                   ->value(module::get_var("phpmailer", "smtp_port"));
    $phpmailer_ssl["use_ssl_checkbox"] = array(t("Use SSL?"), module::get_var("phpmailer", "use_ssl"));
    $phpmailer_tls["use_tls_checkbox"] = array(t("Use TLS?"), module::get_var("phpmailer", "use_tls"));
    $phpmailerSMTP->checklist("phpmailer_use_ssl")
                  ->options($phpmailer_ssl);
	
    $phpmailerSMTP->checklist("phpmailer_use_tls")
                  ->options($phpmailer_tls);
	
    // Add a save button to the form.
    $form->submit("SaveSettings")->value(t("Save"));

    // Return the newly generated form.
    return $form;
  }
}
