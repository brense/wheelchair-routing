<?php

$junctions[0] = new Junction(new LatLong(51.9069300, 4.4733583));
$junctions[1] = new Junction(new LatLong(51.9075357, 4.4752518));
$junctions[2] = new Junction(new LatLong(51.9075907, 4.4754238));
$junctions[3] = new Junction(new LatLong(51.9082273, 4.4774139));
$junctions[4] = new Junction(new LatLong(51.9061500, 4.4738324));
$junctions[5] = new Junction(new LatLong(51.9067910, 4.4758699));
$junctions[6] = new Junction(new LatLong(51.9068377, 4.4760182));
$junctions[7] = new Junction(new LatLong(51.9074355, 4.4779861));
$junctions[8] = new Junction(new LatLong(51.9060274, 4.4739072));
$junctions[9] = new Junction(new LatLong(51.9064968, 4.4753939));
$junctions[10] = new Junction(new LatLong(51.9066687, 4.4759714));
$junctions[11] = new Junction(new LatLong(51.9067119, 4.4761167));
$junctions[12] = new Junction(new LatLong(51.9069184, 4.4767824));
$junctions[13] = new Junction(new LatLong(51.9073159, 4.4780478));
$junctions[14] = new Junction(new LatLong(51.9048389, 4.4746308));
$junctions[15] = new Junction(new LatLong(51.9055414, 4.4761983));
$junctions[16] = new Junction(new LatLong(51.9061161, 4.4763638));
$junctions[17] = new Junction(new LatLong(51.9064448, 4.4771902));
$junctions[18] = new Junction(new LatLong(51.9061074, 4.4774982));
$junctions[19] = new Junction(new LatLong(51.9065381, 4.4784462));
$junctions[20] = new Junction(new LatLong(51.9071338, 4.4769929)); // calandstraat 7
$junctions[21] = new Junction(new LatLong(51.9058291, 4.4768590)); // westerkade 14

// Parklaan
$streets[0] = new Street('Parklaan');
$streets[0]->addSection(new Section($junctions[0], $junctions[1], 'Tegels'));
$streets[0]->addSection(new Section($junctions[1], $junctions[2], 'Tegels'));
$streets[0]->addSection(new Section($junctions[2], $junctions[3], 'Tegels'));

// Calandstraat, Veerhaven to Westerlaan
$streets[1] = new Street('Calandstraat');
$streets[1]->addSection(new Section($junctions[7], $junctions[20], 'Tegels'));
$streets[1]->addSection(new Section($junctions[20], $junctions[6], 'Tegels'));
$streets[1]->addSection(new Section($junctions[6], $junctions[5], 'Tegels'));
$streets[1]->addSection(new Section($junctions[5], $junctions[4], 'Tegels'));

// Calandstraat, Westerlaan to Veerhaven
$streets[2] = new Street('Calandstraat');
$streets[2]->addSection(new Section($junctions[8], $junctions[9], 'Tegels'));
$streets[2]->addSection(new Section($junctions[9], $junctions[10], 'Tegels'));
$streets[2]->addSection(new Section($junctions[10], $junctions[11], 'Tegels'));
$streets[2]->addSection(new Section($junctions[11], $junctions[12], 'Tegels'));
$streets[2]->addSection(new Section($junctions[12], $junctions[13], 'Tegels'));

// Parkstraat, Parklaan to Calandstraat
$streets[3] = new Street('Parkstraat');
$streets[3]->addSection(new Section($junctions[1], $junctions[5], 'Tegels'));
$streets[3]->addSection(new Section($junctions[5], $junctions[10], 'Slechte kruising'));

// Parkstraat, Calandstraat to Parklaan
$streets[4] = new Street('Parkstraat');
$streets[4]->addSection(new Section($junctions[11], $junctions[6], 'Slechte kruising'));
$streets[4]->addSection(new Section($junctions[6], $junctions[2], 'Tegels'));

// Westerlaan
$streets[5] = new Street('Westerlaan');
$streets[5]->addSection(new Section($junctions[0], $junctions[4], 'Tegels'));
$streets[5]->addSection(new Section($junctions[4], $junctions[8], 'Tegels'));
$streets[5]->addSection(new Section($junctions[8], $junctions[14], 'Tegels'));

// Veerhaven
$streets[6] = new Street('Veerhaven');
$streets[6]->addSection(new Section($junctions[19], $junctions[13], 'Tegels'));
$streets[6]->addSection(new Section($junctions[13], $junctions[7], 'Tegels'));
$streets[6]->addSection(new Section($junctions[7], $junctions[3], 'Tegels'));

// Westerkade
$streets[7] = new Street('Westerkade');
$streets[7]->addSection(new Section($junctions[14], $junctions[15], 'Keien'));
$streets[7]->addSection(new Section($junctions[15], $junctions[21], 'Keien'));
$streets[7]->addSection(new Section($junctions[21], $junctions[18], 'Keien'));
$streets[7]->addSection(new Section($junctions[18], $junctions[19], 'Keien'));

// Zeemanstraat
$streets[8] = new Street('Zeemanstraat');
$streets[8]->addSection(new Section($junctions[12], $junctions[17], 'Tegels'));
$streets[8]->addSection(new Section($junctions[17], $junctions[18], 'Tegels'));

// Rivierstraat
$streets[9] = new Street('Rivierstraat');
$streets[9]->addSection(new Section($junctions[9], $junctions[15], 'Tegels'));

// Javastraat
$streets[10] = new Street('Javastraat');
$streets[10]->addSection(new Section($junctions[17], $junctions[16], 'Tegels'));