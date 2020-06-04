<?php
  class Song {

    private $con;
    private $id;
    private $mysqliData;
    private $title;
    private $artistId;
    private $albumId;
    private $genre;
    private $duration;
    private $path;

    public function __construct($con, $id) {
      $this->con = $con;
      $this->id = $id;

      $Query = mysqli_query($this->con, "SELECT * FROM songs WHERE id='$this->id'");
      $this->mysqliData = mysqli_fetch_array($Query);
      $this->title = $this->mysqliData['title']; 
      $this->artistId = $this->mysqliData['artistId']; 
      $this->albumId = $this->mysqliData['albumId']; 
      $this->genre = $this->mysqliData['genre']; 
      $this->duration = $this->mysqliData['duration']; 
      $this->path = $this->mysqliData['path']; 
    }

    public function getTitle() {
      return $this->title;
    }

    public function getArtist() {
      return new Artist($this->con, $this->artistId);
    }

    public function getAlbum() {
      return new Album($this->con, $this->albumId);
    }

    public function getPath() {
      return $this->path;
    }

    public function getDuration() {
      return $this->duration;
    }

    public function getMysqliData() {
      return $this->mysqliData;
    }

    public function getGenre() {
      return $this->genre;
    }

  }