# Wasted Votes

The initial draft of the backend that runs the https://wastedvotes.reformfoundation.org system. I haven't had a chance to put much documentation together (coded it over a few days with just a few hours to give).

This is a very, very basic and small project; the code is mainly here for anyone to analyse the mathematical electoral algorithms for how I have created marginality indexes based on the data. (Warning, the code is far from my best.)

## To get it working

1. Please check the MySQL details in src/models/Base.php match your database login (lines 19-22).
2. Import the various MySQL databases (.sql files).
3. You might want to run a composer install/composer upgrade (though I haven't made a .gitignore file).
4. If you wish to repopulate the wasted_votes_2010 table, truncate it and run index.php/?q=generate.

### PHPUnit

In the tests directory there are some PHPUnit tests which may be useful.

### Autoloading

I have used the composer autoloader in PSR-4 compliant mode.

## Licence

    The open-source backend to The Reform Foundation's Wasted Votes system.
    Copyright (C) 2015  Junade Ali

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
