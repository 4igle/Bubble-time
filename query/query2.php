<?php

$query2 =	'SELECT

				discussions.*

			FROM

				discussions, groupe

			WHERE 

				discussions.id = :iddisc
				AND
				( 
					(
						discussions.groupe = 0 AND groupe.id = 1
					)
					OR
					(
						discussions.groupe = 1 AND groupe.iddisc = :iddisc
						AND
						(
							groupe.id1 = :id OR groupe.id2 = :id OR groupe.id3 = :id OR groupe.id4 = :id OR groupe.id5 = :id OR groupe.id6 = :id OR groupe.id7 = :id OR groupe.id8 = :id OR groupe.id9 = :id OR groupe.id10 = :id OR groupe.id11 = :id OR groupe.id12 = :id OR groupe.id13 = :id OR groupe.id14 = :id OR groupe.id15 = :id OR groupe.id16 = :id OR groupe.id17 = :id OR groupe.id18 = :id OR groupe.id19 = :id OR groupe.id20 = :id OR groupe.id21 = :id OR groupe.id22 = :id OR groupe.id23 = :id OR groupe.id24 = :id OR groupe.id25 = :id OR groupe.id26 = :id OR groupe.id27 = :id OR groupe.id28 = :id OR groupe.id29 = :id OR groupe.id30 = :id
						)
					)
				)';
 ?>