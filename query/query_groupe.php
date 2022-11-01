<?php

$query =	'SELECT

				discussions.*, discussions.groupe AS grp, discussions.latest AS vdate, msg.latest, msg.contenu AS message, msg.iddisc, groupe.id1 AS gid1, groupe.id2 AS gid2, groupe.id3 AS gid3, groupe.id4 AS gid4, groupe.id5 AS gid5, groupe.id6 AS gid6, groupe.id7 AS gid7, groupe.id8 AS gid8, groupe.id9 AS gid9, groupe.id10 AS gid10, groupe.id11 AS gid11, groupe.id12 AS gid12, groupe.id13 AS gid13, groupe.id14 AS gid14, groupe.id15 AS gid15, groupe.id16 AS gid16, groupe.id17 AS gid17, groupe.id18 AS gid18, groupe.id19 AS gid19, groupe.id20 AS gid20, groupe.id21 AS gid21, groupe.id22 AS gid22, groupe.id23 AS gid23, groupe.id24 AS gid24, groupe.id25 AS gid25, groupe.id26 AS gid26, groupe.id27 AS gid27, groupe.id28 AS gid28, groupe.id29 AS gid29, groupe.id30 AS gid30, groupe.non_lu1, groupe.non_lu2, groupe.non_lu3, groupe.non_lu4, groupe.non_lu5, groupe.non_lu6, groupe.non_lu7, groupe.non_lu8, groupe.non_lu9, groupe.non_lu10, groupe.non_lu11, groupe.non_lu12, groupe.non_lu13, groupe.non_lu14, groupe.non_lu15, groupe.non_lu16, groupe.non_lu17, groupe.non_lu18, groupe.non_lu19, groupe.non_lu20, groupe.non_lu21, groupe.non_lu22, groupe.non_lu23, groupe.non_lu24, groupe.non_lu25, groupe.non_lu26, groupe.non_lu27, groupe.non_lu28, groupe.non_lu29, groupe.non_lu30 

			FROM

				discussions, msg, groupe

			WHERE
			(			
				discussions.groupe = 1 AND groupe.iddisc = discussions.id 
				AND
				(
					groupe.id1 = :id OR groupe.id2 = :id OR groupe.id3 = :id OR groupe.id4 = :id OR groupe.id5 = :id OR groupe.id6 = :id OR groupe.id7 = :id OR groupe.id8 = :id OR groupe.id9 = :id OR groupe.id10 = :id OR groupe.id11 = :id OR groupe.id12 = :id OR groupe.id13 = :id OR groupe.id14 = :id OR groupe.id15 = :id OR groupe.id16 = :id OR groupe.id17 = :id OR groupe.id18 = :id OR groupe.id19 = :id OR groupe.id20 = :id OR groupe.id21 = :id OR groupe.id22 = :id OR groupe.id23 = :id OR groupe.id24 = :id OR groupe.id25 = :id OR groupe.id26 = :id OR groupe.id27 = :id OR groupe.id28 = :id OR groupe.id29 = :id OR groupe.id30 = :id
				)			
			)
			AND msg.latest = 1 AND msg.iddisc = discussions.id ORDER BY discussions.latest DESC';
 ?>