<?php


namespace HomeCEU\DocumentCreator;


interface Persistence {
  public function generateId();
  public function persist($data);
  public function retrieve($id);
  public function delete($id);
}