<?php
require_once 'header.php';
?>
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>Manage your decks</h1>
        <p>This site is a mobile-friendly tournament organizer and deck management utility for Android: Netrunner. The site will help to manage your tournaments or play-groups and also provide local and global statistics on card usage and win/loss ratios.</p>
        <p><a href="about.php" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
      </div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
          <h2>Organize a Tournament</h2>
          <p>Use Datarunner to organize a tournament for your FLGS or personal play-group. Tournaments are built using the Netrunner tournament rules and uses computerized randomization to handle situations like starting matchups.</p>
          <p><a class="btn" href="organize.php">Organize a Tournament &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>Stats</h2>
          <p>Provide statistics to compettitive players of Android: Netrunner on play styles, most used cards, and effective strategies. Datarunner can track both worldwide play styles and the play styles of your local group. Every person who uses Datarunner adds to the statistics pool! Use these stats to improve your compettitive play.</p>
          <p><a class="btn" href="stats.php">View your stats &raquo;</a></p>
       </div>
        <div class="span4">
          <h2>Manage Decks</h2>
          <p>With Datarunner you can build and save your decklists for future use. You can also export and import them using Octgn.net files and text files so that you can add them to Octgn or other sites like cardgamedb.com!</p>
          <p><a class="btn" href="decks.php">Manage your decks &raquo;</a></p>
        </div>
      </div>
<?php

require_once 'footer.php';

?>
