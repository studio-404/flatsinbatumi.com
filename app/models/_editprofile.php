<?php 
class _editprofile
{
	public $data;
	private $form;

	public function index()
	{
		$this->form = sprintf(
			"<form action=\"\" method=\"post\" id=\"profileEditForm\">"
		);

		$this->form .= "<div class=\"output-message\" style=\"background-color: rgba(20, 202, 33, 0.75); clear:both;\">&nbsp;</div>";

		$this->form .= "<label>Эл. адрес*</label>";
		$this->form .= sprintf(
			"<input type=\"text\" class=\"form-control\" name=\"email\" id=\"email\" value=\"%s\" disabled=\"disabled\" />",
			$this->data["email"]
		);
		$this->form .= "</div>";


		$this->form .= "<div class=\"form-group\">";
		$this->form .= "<label>Имя*</label>";
		$this->form .= sprintf(
			"<input type=\"text\" class=\"form-control\" name=\"firstname\" id=\"firstname\" value=\"%s\" />",
			$this->data["firstname"]
		);
		$this->form .= "</div>";

		$this->form .= "<div class=\"form-group\">";
		$this->form .= "<label>Мобильный*</label>";
		$this->form .= sprintf(
			"<input type=\"text\" class=\"form-control\" name=\"mobile\" id=\"mobile\" value=\"%s\" />",
			$this->data["phone"]
		);
		$this->form .= "</div>";


		$this->form .= "<div class=\"form-group\">";		
		$this->form .= "<button class=\"btn btn-success pull-right btn-block btn-lg updateProfileButton\" style=\"font-size: 18px;\">Обновить</button>";
		$this->form .= "<div style=\"clear: both\"></div>";
		$this->form .= "</div>";
		


		$this->form .= "</form>";

		return $this->form;
	}
}