<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movies")
 */
class Movie
{
    const TYPE_MOVIE = 'movie';
    const TYPE_SERIAL = 'serial';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="title_org", type="string", length=255, nullable=false)
     */
    private $titleOrg;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="smallint", nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="decimal", scale=1, nullable=false)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=6, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=500, nullable=false)
     */
    private $poster;

    /**
     * @var Genre[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre")
     * @ORM\JoinTable(
     *     name = "movies_genres",
     *     joinColumns={@ORM\JoinColumn(name="movies_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="genres_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $genre;

    /**
     * @var Director[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Director")
     * @ORM\JoinTable(
     *     name="movies_directors",
     *     joinColumns={@ORM\JoinColumn(name="movies_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="director_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $director;


    public function __construct()
    {
        $this->genre = new ArrayCollection();
        $this->director = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitleOrg(): string
    {
        return $this->titleOrg;
    }

    public function setTitleOrg(string $titleOrg): self
    {
        $this->titleOrg = $titleOrg;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function addGenre(Genre $genre): self
    {
        $this->genre->add($genre);
        return $this;
    }

    public function getDirector()
    {
        return $this->director;
    }

    public function setDirector($director): self
    {
        $this->director = $director;
        return $this;
    }

    public function addDirector(Director $director): self
    {
        $this->director->add($director);
        return $this;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getPoster(): string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;
        return $this;
    }
}