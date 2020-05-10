<?php 
class _editpassword
{
	private $form;

	public function index()
	{
		$this->form = sprintf(
			"<form action=\"\" method=\"post\" id=\"passwordEditForm\">"
		);

		$this->form .= "<div class=\"output-message\" style=\"background-color: rgba(20, 202, 33, 0.75); clear:both;\">&nbsp;</div>";

		$this->form .= "<div class=\"form-group\">";
		$this->form .= "<label>Текущий пароль*</label>";
		$this->form .= sprintf(
			"<input type=\"password\" class=\"form-control\" name=\"current_password\" id=\"current_password\" value=\"\" />"
		);
		$this->form .= "</div>";


		$this->form .= "<div class=\"form-group\">";
		$this->form .= "<label>Новый пароль*</label>";
		$this->form .= sprintf(
			"<input type=\"password\" class=\"form-control\" name=\"new_password\" id=\"new_password\" value=\"\" />"
		);
		$this->form .= "</div>";

		$this->form .= "<div class=\"form-group\">";
		$this->form .= "<label>Подтвердите Пароль*</label>";
		$this->form .= sprintf(
			"<input type=\"password\" class=\"form-control\" name=\"confirm_password\" id=\"confirm_password\" value=\"\" />"
		);
		$this->form .= "</div>";


		$this->form .= "<div class=\"form-group\">";		
		$this->form .= "<button class=\"btn btn-success pull-right btn-block btn-lg updatePasswordButton\" style=\"font-size: 18px;\">Обновить</button>";
		$this->form .= "<div style=\"clear: both\"></div>";
		$this->form .= "</div>";
		


		$this->form .= "</form>";

		return $this->form;
	}
}