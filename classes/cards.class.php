<?php
require_once 'database.class.php';

class deck {
	public $deck_id;
	public $identity;
	public $side;
	public $creator;
	public $name;
	public $cards;


	public function __construct($id, $side = null, $creator = null, $name = null) {
	
		// make a new deck
		if (!is_null($side)) {
			$this->side = $side;
			$this->creator = $creator;
			$this->name = $name;
			if ($this->side == 'corp') {
				$this->identity = new corpid($id, 'corpid');
			} else {
				$this->identity = new runnerid($id, 'runnerid');
			}
		} else {
		
			// load deck from database
			$db = new database();
			$db->beginTransaction();
			$db->query('SELECT * FROM decks WHERE deck_id = :deck_id');
			$db->bind(':deck_id', $id);
			try {
				$deck = $db->single();
				$this->deck_id = $deck['deck_id'];
				$this->creator = $deck['player_id'];
				if ($deck['faction'] == 'Anarch' || $deck['faction'] == 'Criminal' || $deck['faction'] == 'Shaper') {
					$this->side = 'runner';
				} else {
					$this->side = 'corp';
				}
				$this->name = $deck['name'];
				if ($this->side == 'corp') {
					$this->identity = new corpid($deck['identity'], 'corpid');
				} else {
					// $this->identity = new runnerid($deck['identity'], 'runnerid');
				}
			} catch (Exception $e) {
				echo 'Error selecting deck: '.$e->getMessage();
			}
			
			// load cards
			$db->query('SELECT * FROM deck_list WHERE :deck_id = :deck_id ORDER BY card_type, card_id');
			$db->bind(':deck_id', $this->deck_id);
			try {
				$cards = $db->resultset();
			} catch (Exception $e) {
				echo 'Error loading cards into deck: '.$e->getMessage();
			}
			foreach ($cards as $k => $v) {
				$this->cards[] = new $v['card_type']($v['card_id'], $v['card_type']);
			}
		}
	}

	public function addcard ($id, $type, $qty = 1) {
		while ($qty > 0) {
			$this->cards[] = new $type($id, $type);
			$qty--;
		}
	}

	public function removecard ($name, $qty = 1) {
		foreach ($this->cards as $key => $value) {
			if ($this->cards[$key]->name == $name) {
				if ($qty > 0) {
					unset($this->cards[$key]);
					$qty--;
				}
			}
		}
	}

	public function savedeck() {
		$db = new database();
		$db->beginTransaction();
		
		// insert into decks
		$db->query('INSERT INTO decks (player_id, name, identity, faction) VALUES (:player_id, :name, :identity, :faction)');
		$db->bind(':player_id', $this->creator);
		$db->bind(':name', $this->name);
		$db->bind(':identity', $this->identity->card_id);
		$db->bind(':faction', $this->identity->faction);
		try {
			$db->execute();
			$this->deck_id = $db->lastInsertId();
		} catch (Exception $e) {
			echo 'Error saving deck to table: decks - '.$e->getMessage();
		}
		
		// start card insertion
		foreach ($this->cards as $k => $v) {
			$db->query('INSERT INTO deck_list (deck_id, card_id, card_type) VALUES (:deck_id, :card_id, :card_type)');
			$db->bind(':deck_id', $this->deck_id);
			$db->bind(':card_id', $v->card_id);
			$db->bind(':card_type', get_class($v));
			try {
				$db->execute();
			} catch (Exception $e) {
				echo 'Error inserting card '.$this->cards[$k].': '.$e->getMessage();
			}
		}
		$db->endTransaction();
	}
}

abstract class card {
	public static $card_type = 'card';
	public $card_id;
	public $name;
	public $text;
	public $flavor_text = null;
	public $faction;
	public $influence = null;
	public $set;
	public $set_number;
	public $pack_quantity;
	public $unique = null;

	public function __construct($card_id, $type) {
		$db = new database();
		switch ($type) {
			// corp cards
			case 'agenda':
				$db->query('SELECT * FROM cards_agenda WHERE card_id = :card_id');
				break;
			case 'asset':
				$db->query('SELECT * FROM cards_asset WHERE card_id = :card_id');
				break;
			case 'corpid':
				$db->query('SELECT * FROM cards_corpid WHERE card_id = :card_id');
				break;
			case 'ice':
				$db->query('SELECT * FROM cards_ice WHERE card_id = :card_id');
				break;
			case 'operation':
				$db->query('SELECT * FROM cards_operation WHERE card_id = :card_id');
				break;
			case 'upgrade':
				$db->query('SELECT * FROM cards_upgrade WHERE card_id = :card_id');
				break;
			// runner cards
			case 'runnerid':
				$db->query('SELECT * FROM cards_runnerid WHERE card_id = :card_id');
				break;
			case 'hardware':
				$db->query('SELECT * FROM cards_hardware WHERE card_id = :card_id');
				break;
			case 'resources':
				$db->query('SELECT * FROM cards_resources WHERE card_id = :card_id');
				break;
			case 'program':
				$db->query('SELECT * FROM cards_program WHERE card_id = :card_id');
				break;
			case 'event':
				$db->query('SELECT * FROM cards_event WHERE card_id = :card_id');
				break;
		}
		$db->bind(':card_id', $card_id);
		try {
			$row = $db->single();
			if ($db->rowCount() == 0) {
				throw new Exception('Failed to select card '.$card_id.' from table '.$type);
			}
			foreach ($row as $k => $v) {
				if (property_exists($this, $k)) {
					$this->$k = $v;
				}
			}
		} catch (Exception $e) {
			echo 'Error creating '.$type.': '.$e->getMessage();
		}
	}
	
	public function image() {
		return 'cards/0'.$this->set_number.'.png';
	}
	
	public static function all_ids() {
		$db = new database();
		$table = static::$card_type;
		$db->query('SELECT card_id FROM cards_'.$table);
		try {
			$row = $db->resultset();
		} catch (Exception $e) {
			echo 'Error returning all ids for cards_'.$table.': '.$e->getMessage();
		}
		return $row;
	}
	
}

class agenda extends card {
	public static $card_type = 'agenda';
	public $advancement_requirement;
	public $agenda_points;
	public $sub_type;

	public function insertCard() {
		$db = new database();
		$db->query('INSERT INTO cards_agenda (
			name,
			advancement_requirement,
			agenda_points,
			sub_type,
			text,
			flavor_text,
			faction,
			influence,
			set,
			set_number,
			pack_quantity,
			unique
		) VALUES (
			:name,
			:advancement_requirement,
			:agenda_points,
			:sub_type,
			:text,
			:flavor_text,
			:faction,
			:influence,
			:set,
			:set_number,
			:pack_quantity,
			:unique
		)');
		$db->bind(':name', $this->name);
		$db->bind(':advancement_requirement', $this->advancement_requirement);
		$db->bind(':agenda_points', $this->agenda_points);
		$db->bind(':sub_type', $this->sub_type);
		$db->bind(':text', $this->text);
		$db->bind(':flavor_text', $this->flavor_text);
		$db->bind(':faction', $this->faction);
		$db->bind(':influence', $this->influence);
		$db->bind(':set', $this->set);
		$db->bind(':set_number', $this->set_number);
		$db->bind(':pack_quantity', $this->pack_quantity);
		$db->bind(':unique', $this->unique);
	}
}

class asset extends card {
	public static $card_type = 'asset';
	public $cost;
	public $sub_type;
	public $trash_cost;
}

class corpid extends card {
	public static $card_type = 'corpid';
	public $subtitle;
	public $deck_size;
	public $influence_limit;
}

class ice extends card {
	public static $card_type = 'ice';
	public $cost;
	public $type;
	public $strength;
}

class operation extends card {
	public static $card_type = 'operation';
	public $cost;
	public $sub_type;
}

class upgrade extends card {
	public static $card_type = 'upgrade';
	public $sub_type;
	public $trash_cost;
}

// runner classes
class runnerid extends card {
	public static $card_type = 'runnerid';
	public $subtitle;
	public $sub_type;
	public $deck_size;
	public $influence_limit;
	public $link;
}

class hardware extends card {
	public static $card_type = 'hardware';
	public $sub_type;
	public $cost;
}

class resources extends card {
	public static $card_type = 'resources';
	public $sub_type;
	public $cost;
}

class program extends card {
	public static $card_type = 'program';
	public $cost;
	public $memory;
	public $sub_type;
	public $strength;
}

class event extends card {
	public static $card_type = 'event';
	public $cost;
	public $sub_type;
}

?>
