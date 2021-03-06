<?php namespace App\Collection;

use App\Entity\BookFile;

class BookFiles extends Entities {

	/** @return static|BookFile[] */
	public function onlyNew() {
		return $this->filter(function (BookFile $bookFile) {
			return $bookFile->isNew();
		});
	}

	/** @return static|BookFile[] */
	public function notNew() {
		return $this->filter(function (BookFile $bookFile) {
			return !$bookFile->isNew();
		});
	}
}
