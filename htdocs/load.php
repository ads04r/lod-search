<?php
error_reporting(E_ALL);
require('sparqllib.php');

$data = json_decode(file_get_contents(dirname(dirname(__FILE__)) . "/data/prickles.json"), true);
$data2 = json_decode(file_get_contents(dirname(dirname(__FILE__)) . "/data/datasets.json"), true);

//$endpoint = 'http://sparql.data.southampton.ac.uk/';

/*
$data = sparql_get($endpoint, "
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>

SELECT * WHERE {
  GRAPH ?g {
    {
      ?uri rdfs:label ?label .
    } UNION {
      ?uri foaf:name ?label .
    } UNION {
      ?uri rdfs:comment ?label .
    }
    OPTIONAL {
      ?uri a ?t .
    }
    FILTER regex(?label, '$term', 'i')
  }
}
ORDER BY ?uri");


$data2 = sparql_get($endpoint, "
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dc: <http://purl.org/dc/terms/>

SELECT distinct * WHERE{

 ?dataset_uri rdf:type <http://www.w3.org/ns/dcat#Dataset> .
 ?dataset_uri dc:title ?dataset_name .
 ?dataset_uri rdfs:label ?dataset_label
}");
*/

foreach($data2 as $row)
{
	@$datasets[$row['dataset_uri']."/latest"] = $row['dataset_name'];
}

$rankings = unserialize(file_get_contents('total.txt'));

foreach($data as $row)
{
	foreach($row['types'] as $type)
	{
		if(strlen(stristr($row['title'], $term)) == 0)
		{
			continue;
		}
		@$founduris[$row['uri']] = $rankings[$row['uri']];
		@$metadata[$row['uri']]['types'][] = $type;
		@$metadata[$row['uri']]['graphs'][] = $row['dataset'] . "/latest";
		@$metadata[$row['uri']]['labels'][] = $row['title'];
	}
}

unset($rankings);

arsort($founduris);
?>
