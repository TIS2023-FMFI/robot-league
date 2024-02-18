ALTER TABLE teams
ADD city varchar(255),
ADD street_name varchar(255),
ADD zip varchar(15),
ADD category varchar(255);

ALTER TABLE comments 
ADD internal_comment varchar(255);


ALTER TABLE solutions 
ADD best_assignment_number tinyint(1);