<?php

$query =	'SELECT

				discussions.*, discussions.groupe AS grp, discussions.latest AS vdate, msg.latest, msg.contenu AS message, msg.iddisc 

			FROM

				discussions, msg, groupe

			WHERE
			(
				(
					discussions.id1 = :id OR discussions.id2 = :id
				)
				AND discussions.groupe = 0 AND groupe.id = 1
			)
			AND msg.latest = 1 AND msg.iddisc = discussions.id ORDER BY discussions.latest DESC';
 ?>