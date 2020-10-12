<?php


namespace HomeCEU\DTS;


interface Persistence {
  public function generateId();
  public function persist($data);
  public function retrieve($id, array $cols=['*']);
  public function find(array $filter, array $cols=['*']);
  public function search(array $searchCols, string $searchSearchString, array $cols=['*']);
  public function delete($id);
}