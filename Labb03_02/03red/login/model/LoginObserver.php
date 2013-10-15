<?php

namespace login\model;


interface LoginObserver {
	public function loginFailed();
	public function loginOK($info);
}