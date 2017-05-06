<?php namespace App\Entity;

use App\Php\Looper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

trait WithBookFiles {

	use WithBookCovers;
	use WithBookScans;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $fullContent;

	/**
	 * @Vich\UploadableField(mapping="fullcontent", fileNameProperty="fullContent")
	 * @var File
	 */
	private $fullContentFile;

	/** @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file */
	public function setFullContentFile(File $file = null) {
		$this->fullContentFile = $file;
		if ($file) {
			$this->markAsChanged();
		}
	}
	public function setFullContent($fullContent) { $this->fullContent = $fullContent; }

	protected function updateNbFiles() {
		$this->updateNbCovers();
		$this->updateNbScans();
	}

	public function setCreatorByNewFiles($user) {
		$setCreatedBy = function (BookFile $file) use ($user) {
			if ($file->isNew()) {
				$file->setCreatedBy($user);
			}
		};
		Looper::forEachValue($this->scans, $setCreatedBy);
		Looper::forEachValue($this->covers, $setCreatedBy);
		Looper::forEachValue($this->newCovers, $setCreatedBy);
	}

	protected function filesToArray() {
		return [
			'cover' => $this->cover,
			'backCover' => $this->backCover,
			'otherCovers' => $this->getOtherCovers()->toArray(),
			'scans' => $this->getScans()->toArray(),
			'nbScans' => $this->nbScans,
		];
	}

	abstract protected function markAsChanged();
}