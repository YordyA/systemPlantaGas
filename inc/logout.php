<?php
if (isset($peticion)) {
	session_destroy();
	if (headers_sent()) {
		echo "<script> window.location.href='./login'; </script>";
	} else {
		header("Location: ./login");
	}
} else {
	include '../modulos/sessionStart.php';
	session_destroy();
	if (headers_sent()) {
		echo "<script> window.location.href='../index'; </script>";
	} else {
		header("Location: ../");
	}
}