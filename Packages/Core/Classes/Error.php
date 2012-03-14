<?php

namespace Package\Core;

class Error{

	public function handle($handle){
		//Let's load the error view if the environment is development, otherwise load a generic page error view.
		$this->view->template = 'error/dev';
		$this->view->content = 'Undefined Index at line 2';
	}

}