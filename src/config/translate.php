<?php

class Translate {
	function init_translate() {
		// Error 404
		$this->set("error-404-title", "Stránka sa nenašla!", "Page not found!", "Seite nicht gefunden");
		$this->set("error-404-content", "Skúste to neskôr.", "Try later again.", "Versuchen Sie es später erneut");
		
		
		// Header
		$this->set("title", "Robotická liga", "Robot league", "Roboterliga");
		$this->set("keywords", "lego, robotická liga", "robot league, lego", "roboterliga", "lego");
		$this->set("author", "FMFI UK Bratislava", "Comenius University Bratislava", "Comenius University Bratislava");

		
		// Navigator
		$this->set("nav_band", "Robotická Liga", "Robot League", "Roboterliga");
		$this->set("nav_assingments", "Zadania", "Assignments", "Aufgaben");
		$this->set("nav_results", "Výsledky", "Results", "Ergebnisse");
		$this->set("nav_archive", "Archív", "Archive", "Archiv");
        $this->set("nav_profile", "Profil", "Profile", "Profil");
		$this->set("nav_language", "English", "Deutsch", "Slovensky");
		$this->set("nav_language2", "Deutsch", "Slovensky", "English");
		$this->set("nav_login", "Prihlásiť", "Login", "Login");
		$this->set("nav_users", "Používatelia", "Users", "Benutzer");
		$this->set("nav_teams", "Tímy", "Teams", "Teams");
		$this->set("nav_jury", "Rozhodcovia", "Jury", "Jury");
		$this->set("nav_reset_password", "Poslať nové heslo", "Send new password", "Neues Passwort senden");
		
		
		// Navigator dropdown menu		
		$this->set("nav_assignments_assignment", "Zadanie", "Assignment", "Aufgabe");
		$this->set("nav_assignments_overview", "Prehľad zadaní", "Assignments overview", "Aufgaben Überblick");
        $this->set("nav_assignments_results", "Výsledky", "Results", "Ergebnisse");


        // Navigator - Login
        $this->set("nav_account_mail", "E-mail", "E-mail", "E-mail");
        $this->set("nav_account_password", "Heslo", "Password", "Passwort");
        $this->set("nav_account_registration", "Registrácia", "Registration", "Anmeldung");
        $this->set("nav_account_submit", "Prihlásiť", "Login", "Login");
        $this->set("nav_account_logout", "Odhlásiť", "Logout", "Ausloggen");
    
        // Navigator - Registration
        $this->set("reg_new", "Nová registrácia", "New registration", "Neue Anmeldung");
        $this->set("reg_mail", "E-mail", "E-mail", "E-mail");
        $this->set("reg_password", "Heslo", "Password", "Passwort");
        $this->set("reg_repeat_password", "Zopakuj heslo", "Repeat password", "Passwort wiederholen");
        $this->set("reg_team_name", "Názov tímu", "Team name", "Teamname");
        $this->set("reg_address", "Adresa", "Address", "Adresse");
        $this->set("reg_city", "Mesto", "City", "Stadt");
        $this->set("reg_street_name", "Ulica", "Street", "Straße");
        $this->set("reg_zip", "PSČ", "ZIP Code", "PLZ");
        $this->set("reg_category", "Kategória", "Category", "Kategorie");
        $this->set("reg_about", "Niečo o vás", "About your team", "Über ihr Team");
        $this->set("reg_skleague", "Slovenský tím", "Slovak team", "Slowakische Team");
        $this->set("reg_openleague", "Medzinárodný tím", "International team", "Internationales Team");
        $this->set("reg_rabbits", "Zajace", "Rabbits", "Hasen");
        $this->set("reg_tigers", "Tigre", "Tigers", "Tiger");
        $this->set("reg_register", "Zaregistrovať sa", "Proceed with registration", "Anmelden");
        $this->set("reg_addrdisclaimer", "Adresu potrebujeme evidovať, aby sme Vám vedeli poslať diplom a ceny.", "Please supply the address so that we can send you an award.", "Bitte geben Sie die Adresse an, damit wir Ihnen eine Auszeichnung zusenden können");
        $this->set("gdpr", "GDPR: Robotická liga je on-line platforma na zdieľanie riešení. Za obsah a súhlas " .
                                   " s publikovaním riešení, fotodokumentácie a videodokumentácie zodpovedá ten, kto " .
                                   " riešenia na stránku nahral. Riešenia zostávajú v archíve zverejnené neobmedzene. " .
                                   " Organizátori si vyhradzujú právo vymazať akýkoľvek obsah v prípade potreby a potom, čo " . 
                                   " tím nereaguje na komunikáciu ani do dvoch týždňov. " .
                                   " Robotika.SK okrem toho archivuje e-mailové adresy tímov zadané v registrácii pre účel " .
                                   " komunikácie s tímami. V prípade, že požadujete, aby niektoré fotografie, riešenia, alebo mailové " .
                                   " adresy boli zo stránky a/alebo nášho archívu odstránené, napíšte na e-mail: pavel.petrovic@gmail.com. Účasťou " .
                                   " v súťaži súťažiaci vyjadrujú súhlas s týmito pravidlami.", 
                                   "GDPR: Robot League is an on-line platform for sharing solutions. Persons uploading any content " .
                                   " bear all responsibilites for violations of any kind, as well any required consent of the team members or " .
                                   " their legal representatives. Site maintenance reserves the right " .
                                   " to remove any content if needed after the team shall not respond to our e-mail communication within two weeks. " .
                                   " Otherwise, the content remains in the archive for unlimited period of time. " .
                                   " Robotika.SK archives e-mail addresses of teams entered in the registration for the purpose " .
                                   " of communication with the teams. If you demand that some of your content is removed from the " .
                                   " site, or your e-mail address from our archive, send an e-mail to: info@robotika.sk. With participation " .
                                   " in this contest you confirm to agree and comply with these rules.",
                                   "GDPR: Robot League ist eine Online-Plattform zum Teilen von Lösungen. Personen, die Inhalte hochladen, " .
                                   " tragen alle Verantwortung für Verstöße jeglicher Art sowie die erforderliche Zustimmung der Teammitglieder " .
                                   " oder ihrer gesetzlichen Vertreter. Die Website-Wartung behält sich das Recht vor, Inhalte zu entfernen, " .
                                   " wenn dies erforderlich ist, nachdem das Team nicht innerhalb von zwei Wochen auf unsere E-Mail-Kommunikation " .
                                   " reagiert hat. Ansonsten verbleibt der Inhalt für unbegrenzte Zeit im Archiv. Robotika.SK archiviert " .
                                   " E-Mail-Adressen von Teams, die in der Registrierung zum Zwecke der Kommunikation mit den Teams eingetragen " .
                                   " wurden. Wenn Sie verlangen, dass ein Teil Ihres Inhalts von der Website entfernt wird oder Ihre E-Mail-Adresse " .
                                   " aus unserem Archiv, senden Sie eine E-Mail an: info@robotika.sk. Mit der Teilnahme an diesem Wettbewerb bestätigen " .
                                   " Sie, dass Sie diesen Regeln zustimmen und diese einhalten.");
        $this->set("empty_city", "Vyžaduje sa názov mesta", "City name is required", "Der Name der Stadt ist erforderlich");
        $this->set("empty_desc", "Vyžaduje sa popis tímu", "Team description is required", "Eine Teambeschreibung ist erforderlich");
        $this->set("empty_street", "Vyžaduje sa názov ulice", "Street name is required", "Straßenname ist erforderlich");
        $this->set("invalid_zip", "Vyžaduje sa platné 5-miestne PSČ", "A valid 5-digit ZIP code is required", "Eine gültige 5-stellige Postleitzahl ist erforderlich");
        $this->set("empty_cat", "Kategória je povinná", "Category is required", "Kategorie ist erforderlich");
        $this->set("invalid_mail", "Neplatný email", "Invalid email", "Ungültige E-Mail");
        $this->Set("used_mail", "Email už existuje!", "Email already exists!", "E-Mail existiert bereits!");
        $this->set("short_pw", "Heslo musí mať aspoň 6 znakov!", "Password must have at least 6 characters!", "Das Passwort muss mindestens 6 Zeichen lang sein!");
        $this->Set("diff_pw", "Heslá sa nezhodujú", "Passwords do not match", "Passwörter stimmen nicht überein");
        $this->set("short_team", "Tím musí mať aspoň 5 znakov", "Team name must have at least 5 characters", "Der Teamname muss mindestens 5 Zeichen lang sein");
        $this->set("used_team", "Tento team už existuje!", "This team already exists!", "Dieses Team existiert bereits!");

        // Assignment content
        $this->set("assignment_task", "Úloha", "Task", "Auftrag");
        $this->set("assignment_deadline", "Riešenie možno odovzdávať do", "Deadline of this assignment is set to", "Die Frist für diese Aufgabe ist festgelegt auf");
        $this->set("assignment_time_format", "d.m.Y H:i:s", "Y-m-d H:i:s", "d.m.Y H:i:s");
        $this->set("assignment_solutions", "Riešenia", "Solutions", "Lösungen");
        $this->set("assignment_solutions_update", "Odovzdať riešenie k úlohe", "Upload solution for task", "Lösung für Aufgabe hochladen");
    
    
        // New assignment
        $this->set("new_assignment_sk", "Názov zadania (SK)", "New assignment (SK)", "Neue Aufgabe (SK)");
        $this->set("new_assignment_en", "Názov zadania (EN)", "New assignment (EN)", "Neue_Aufgabe (EN)");
        $this->set("new_assignment_de", "Názov zadania (DE)", "New assignment (DE)", "Neue_Aufgabe (DE)");
        $this->set("new_assignment_description_sk", "Popis zadania (SK)", "Assignment description (SK)", "Aufgabe Beschreibung (SK)");
        $this->set("new_assignment_description_en", "Popis zadania (EN)", "Assignment description (EN)", "Aufgabe Beschreibung (EN)");
        $this->set("new_assignment_description_de", "Popis zadania (DE)", "Assignment description (DE)", "Aufgabe Beschreibung (DE)");
        $this->set("new_assignment_images", "Obrázky", "Images", "Bilder");
        $this->set("new_assignment_youtube", "Youtube linky", "Youtube links", "Youtube links");
        $this->set("new_assignment_save", "Uložiť zadanie", "Save assignment", "Aufgabe speichern");
        $this->set("new_assignment_overview", "Prehľad zadaní", "Assignments overview", "Aufgaben Überblick");
		
		// Assignment overview
		$this->set("assignment_overview_list", "Zoznam zadaní", "Assignment list", "Aufgabenliste");
		$this->set("assignment_overview_new", "Nové zadanie", "New assignment", "Neue Aufgabe");
		$this->set("assignment_overview_publish", "Zverejniť zadanie", "Publish assignment", "Aufgabe publizieren" );
		$this->set("assignment_overview_date_publish", "Dátum zverejnenia", "Publish date", "Veröffentlichungsdatum");
		$this->set("assignment_overview_date_dedline", "Dátum ukončenia", "Deadline date", "Einsendeschluss");
		$this->set("assignment_overview_published", "Zverejnené zadania", "Published assignments", "Veröffentlichte Aufgabe");
		$this->set("assignment_overview_edit", "Upraviť", "Edit", "Redigieren");
		$this->set("assignment_overview_delete", "Zmazať", "Delete", "Löschen");
        $this->set("assignment_overview_past_the_deadline", "Po termíne odovzdania", "Past the deadline", "Nach Ablauf der Frist");

        // Assignment preview
		$this->set("assignment_preview", "Náhľad zadania", "Assignment preview", "Aufgabenvorschau");
		
		// New solutions
		$this->set("solution_assignment", "Zadanie", "Assignment", "Aufgabe");
		$this->set("solution_save_result", "Riešenie bolo uložené", "Solution has been saved", "Lösung wurde gespeichert");
		$this->set("solution_programs", "Programy", "Programs", "Programme");
		$this->set("solution_videos", "Videá", "Videos", "Videos");
		$this->set("solution_photos", "Fotografie", "Photos", "Fotografien");
		$this->set("solution_save", "Odovzdať riešenie", "Save solution", "Lösung speichern");
		$this->set("solution_overview", "Prehľad zadaní", "Assignments overview", "Aufgaben Überblick");
		$this->set("solution_team_name", "Názov tímu", "Team name", "Teamname");
        $this->set("solution_team_info", "O tíme", "About the team", "Über das Team");
		$this->set("solution_rating", "Hodnotenie", "Rating", "Bewertung");
		$this->set("solution_back", "Späť", "Back", "Zurück");
		$this->set("solution_next", "Ďalej", "Next", "Weiter");
        $this->set("solution_addyoutube", "Pridať YouTube linku", "Add YouTube link", "YouTube-Link hinzufügen");
        $this->set("solution_missing_info", "Pred odoslaním riešenia je potrebné mať na profile zadanú kategóriu, poštovú adresu a informácie o tíme."
            , "Before submitting the solution, it is necessary to have the category, postal address, and team information entered on your profile."
            , "Vor dem Einreichen der Lösung ist es erforderlich, die Kategorie, die Postanschrift und die Teaminformationen in Ihrem Profil anzugeben.");
		$this->set("save_rating", "Uložiť hodnotenie", "Save rating", "Bewertung speichern");
		$this->set("rating_was_saved", "Hodnotenie bolo uložené.", "Rating was saved.", "Bewertung wurde gespeichert");
        $this->set("admin_solution_update", "Upraviť riešenie", "Edit solution", "Bearbeiten Sie die Lösung");
		$this->set("internal_comment", "Interný komentár rozhodcu", "Internal referee commentary", "Interner Schiedsrichterkommentar");

        // Solution preview
        $this->set("solution_preview", "Náhľad riešenia", "Solution preview", "Lösungsvorschau");

		// Results
		$this->set("res_sk_liga_max", "Slovenská liga", "Slovak league", "Slowakische Liga");
		$this->set("res_open_liga_max", "Open liga", "Open league", "Open Liga");

		$this->set("res_sk_liga_min", "Slovenská liga - druhá úloha", "Slovak League - Second Task", "Slowakische Liga - Zweites Auftrag");
		$this->set("res_open_liga_min", "Open liga - druhá úloha", "Open league - Second Task", "Open Liga - Zweites Auftrag");

        // Profile
        $this->set("prof_update", "Aktualizovať", "Update", "Aktualisieren");
        $this->set("prof_cancel", "Zrušiť", "Cancel", "Stornieren");
        $this->set("prof_edit", "Aktualizovať profil", "Edit Profile", "Profil bearbeiten");

        // Util
        $this->set("Zajace", "Zajace", "Rabbits", "Hasen");
        $this->set("Tigre", "Tigre", "Tigers", "Tiger");
        $this->set("category_info", "Váš tím je v kategórii ", "Your team is in the category ", "Ihr Team ist in der Kategorie ");
	$this->set("category_empty", "Váš tím nemá zvolenú kategóriu.", "Your team does not have a category selected.", "Für Ihr Team ist keine Kategorie ausgewählt.");
        $this->set("unsaved_changes", "Máte neuložené zmeny.", "You have unsaved changes.", "Sie haben nicht gespeicherte Änderungen.");
	}
}

?>
