<?php


namespace HomeCEU\DTS;


interface Persistence {
  public function generateId();
  public function persist($data);
  public function retrieve($id);
  public function delete($id);

  public function find(array $filter);
}