<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__).'/ArLsfModel.php';

class ArLsfFakeConfigForm extends ArLsfModel
{
    public $names;
    public $mix_names;
    public $locations;
    
    
    public function rules()
    {
        return array(
            array(
                array(
                    'mix_names'
                ), 'safe'
            ),
            array(
                array(
                    'names',
                    'locations',
                ), 'isString'
            )
        );
    }
    
    public function multiLangFields()
    {
        return array(
            'names' => true,
            'locations' => true
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'mix_names' => $this->l('Mix customers first names and last names', 'ArLsfFakeConfigForm'),
            'names' => $this->l('Customers', 'ArLsfFakeConfigForm'),
            'locations' => $this->l('Customer locations', 'ArLsfFakeConfigForm'),
        );
    }
    
    public function attributeDescriptions()
    {
        return array(
            'names' => $this->l('Each from new line. Format: {firstname} - {lastname}', 'ArLsfFakeConfigForm'),
            'locations' => $this->l('Each from new line. Format: {country} - {city} - {state}', 'ArLsfFakeConfigForm'),
        );
    }
    
    public function attributeTypes()
    {
        return array(
            'mix_names' => 'switch',
            'names' => 'textarea',
            'locations' => 'textarea',
        );
    }
    
    public function getFormTitle()
    {
        return $this->l('Fake mode settings', 'ArLsfFakeConfigForm');
    }
    
    public function getCustomer($id_lang, $allowEmpty = false)
    {
        $names = $this->names[$id_lang];
        if (empty($names)) {
            return null;
        }
        $names = explode("\n", $names);
        
        $fns = array();
        $lns = array();
        foreach ($names as $name) {
            $data = explode('-', $name);
            if (isset($data[0]) && trim($data[0])) {
                $fns[] = trim($data[0]);
            }
            if (isset($data[1]) && trim($data[1])) {
                $lns[] = trim($data[1]);
            }
        }
        $fk = array_rand($fns);
        if ($this->mix_names) {
            $lk = array_rand($lns);
        } else {
            $lk = $fk;
        }
        $firstname = $fns[$fk];
        $lastname = $lns[$lk];
        
        $customer = new Customer();
        $customer->firstname = $firstname;
        $customer->lastname = $lastname;
        if ($allowEmpty) {
            if (rand(0, 100) <= $allowEmpty) {
                return null;
            }
        }
        return $customer;
    }
    
    public function getAddress($id_lang)
    {
        $lines = $this->locations[$id_lang];
        $lines = explode("\n", $lines);
        
        $key = array_rand($lines);
        $line = $lines[$key];
        
        $countryString = null;
        $cityString = null;
        $stateString = null;
        
        $data = explode('-', $line);
        if (isset($data[0]) && trim($data[0])) {
            $countryString = trim($data[0]);
        }
        if (isset($data[1]) && trim($data[1])) {
            $cityString = trim($data[1]);
        }
        if (isset($data[2]) && trim($data[2])) {
            $stateString = trim($data[2]);
        }
        
        $address = new Address();
        
        if ($countryString) {
            $sql = new DbQuery();
            $sql->select('c.id_country');
            $sql->from('country', 'c');
            $sql->leftJoin('country_lang', 'cl', 'c.id_country = cl.id_country');
            $sql->where('cl.id_lang = ' . (int)$id_lang . ' AND (cl.name = "' . $countryString . '" OR c.iso_code = "' . $countryString . '")');
            $address->id_country = Db::getInstance()->getValue($sql);
        }
        if ($cityString) {
            $address->city = $cityString;
        }
        
        if ($stateString) {
            $sql = new DbQuery();
            $sql->select('s.id_state');
            $sql->from('state', 's');
            $sql->where('(s.name = "' . $stateString. '" OR s.iso_code = "' . $stateString . '")');
            $address->id_state = Db::getInstance()->getValue($sql);
        }
        
        return $address;
    }
    
    public function attributeDefaults()
    {
        return array(
            'mix_names' => 1,
            'names' => 'James - Smith
John - Johnson
Robert - Williams
Michael - Brown
William - Jones
David - Miller
Richard - Davis
Charles - Garcia
Joseph - Rodriguez
Thomas - Wilson
Christopher - Martinez
Daniel - Anderson
Paul - Taylor
Mark - Thomas
Donald - Hernandez
George - Moore
Kenneth - Martin
Steven - Jackson
Edward - Thompson
Brian - White
Ronald - Lopez
Anthony - Lee
Kevin - Gonzalez
Jason - Harris
Matthew - Clark
Gary - Lewis
Timothy - Robinson
Jose - Walker
Larry - Perez
Jeffrey - Hall
Frank - Young
Scott - Allen
Eric - Sanchez
Stephen - Wright
Andrew - King
Raymond - Scott
Gregory - Green
Joshua - Baker
Jerry - Adams
Dennis - Nelson
Walter - Hill
Patrick - Ramirez
Peter - Campbell
Harold - Mitchell
Douglas - Roberts
Henry - Carter
Carl - Phillips
Arthur - Evans
Ryan - Turner
Roger - Torres
Joe - Parker
Juan - Collins
Jack - Edwards
Albert - Stewart
Jonathan - Flores
Justin - Morris
Terry - Nguyen
Gerald - Murphy
Keith - Rivera
Samuel - Cook
Willie - Rogers
Ralph - Morgan
Lawrence - Peterson
Nicholas - Cooper
Roy - Reed
Benjamin - Bailey
Bruce - Bell
Brandon - Gomez
Adam - Kelly
Harry - Howard
Fred - Ward
Wayne - Cox
Billy - Diaz
Steve - Richardson
Louis - Wood
Jeremy - Watson
Aaron - Brooks
Randy - Bennett
Howard - Gray
Eugene - James
Carlos - Reyes
Russell - Cruz
Bobby - Hughes
Victor - Price
Martin - Myers
Ernest - Long
Phillip - Foster
Todd - Sanders
Jesse - Ross
Craig - Morales
Alan - Powell
Shawn - Sullivan
Clarence - Russell
Sean - Ortiz
Philip - Jenkins
Chris - Gutierrez
Johnny - Perry
Earl - Butler
Jimmy - Barnes
Antonio - Fisher
Danny - Henderson
Bryan - Coleman
Tony - Simmons
Luis - Patterson
Mike - Jordan
Stanley - Reynolds
Leonard - Hamilton
Nathan - Graham
Dale - Kim
Manuel - Gonzales
Rodney - Alexander
Curtis - Ramos
Norman - Wallace
Allen - Griffin
Marvin - West
Vincent - Cole
Glenn - Hayes
Jeffery - Chavez
Travis - Gibson
Jeff - Bryant
Chad - Ellis
Jacob - Stevens
Lee - Murray
Melvin - Ford
Alfred - Marshall
Kyle - Owens
Francis - Mcdonald
Bradley - Harrison
Jesus - Ruiz
Herbert - Kennedy
Frederick - Wells
Ray - Alvarez
Joel - Woods
Edwin - Mendoza
Don - Castillo
Eddie - Olson
Ricky - Webb
Troy - Washington
Randall - Tucker
Barry - Freeman
Alexander - Burns
Bernard - Henry
Mario - Vasquez
Leroy - Snyder
Francisco - Simpson
Marcus - Crawford
Micheal - Jimenez
Theodore - Porter
Clifford - Mason
Miguel - Shaw
Oscar - Gordon
Jay - Wagner
Jim - Hunter
Tom - Romero
Calvin - Hicks
Alex - Dixon
Jon - Hunt
Ronnie - Palmer
Bill - Robertson
Lloyd - Black
Tommy - Holmes
Leon - Stone
Derek - Meyer
Warren - Boyd
Darrell - Mills
Jerome - Warren
Floyd - Fox
Leo - Rose
Alvin - Rice
Tim - Moreno
Wesley - Schmidt
Gordon - Patel
Dean - Ferguson
Greg - Nichols
Jorge - Herrera
Dustin - Medina
Pedro - Ryan
Derrick - Fernandez
Dan - Weaver
Lewis - Daniels
Zachary - Stephens
Corey - Gardner
Herman - Payne
Maurice - Kelley
Vernon - Dunn
Roberto - Pierce
Clyde - Arnold
Glen - Tran
Hector - Spencer
Shane - Peters
Ricardo - Hawkins
Sam - Grant
Rick - Hansen
Lester - Castro
Brent - Hoffman
Ramon - Hart
Charlie - Elliott
Tyler - Cunningham
Gilbert - Knight
Gene - Bradley
Marc - Carroll
Reginald - Hudson
Ruben - Duncan
Brett - Armstrong
Angel - Berry
Nathaniel - Andrews
Rafael - Johnston
Leslie - Ray
Edgar - Lane
Milton - Riley
Raul - Carpenter
Ben - Perkins
Chester - Aguilar
Cecil - Silva
Duane - Richards
Franklin - Willis
Andre - Matthews
Elmer - Chapman
Brad - Lawrence
Gabriel - Garza
Ron - Vargas
Mitchell - Watkins
Roland - Wheeler
Arnold - Larson
Harvey - Carlson
Jared - Harper
Adrian - George
Karl - Greene
Cory - Burke
Claude - Guzman
Erik - Morrison
Darryl - Munoz
Jamie - Jacobs
Neil - Obrien
Jessie - Lawson
Christian - Franklin
Javier - Lynch
Fernando - Bishop
Clinton - Carr
Ted - Salazar
Mathew - Austin
Tyrone - Mendez
Darren - Gilbert
Lonnie - Jensen
Lance - Williamson
Cody - Montgomery
Julio - Harvey
Kelly - Oliver
Kurt - Howell
Allan - Dean
Nelson - Hanson
Guy - Weber
Clayton - Garrett
Hugh - Sims
Max - Burton
Dwayne - Fuller
Dwight - Soto
Armando - Mccoy
Felix - Welch
Jimmie - Chen
Everett - Schultz
Jordan - Walters
Ian - Reid
Wallace - Fields
Ken - Walsh
Bob - Little
Jaime - Fowler
Casey - Bowman
Alfredo - Davidson
Alberto - May
Dave - Day
Ivan - Schneider
Johnnie - Newman
Sidney - Brewer
Byron - Lucas
Julian - Holland
Isaac - Wong
Morris - Banks
Clifton - Santos
Willard - Curtis
Daryl - Pearson
Ross - Delgado
Virgil - Valdez
Andy - Pena
Marshall - Rios
Salvador - Douglas
Perry - Sandoval
Kirk - Barrett
Sergio - Hopkins
Marion - Keller
Tracy - Guerrero
Seth - Stanley
Kent - Bates
Terrance - Alvarado
Rene - Beck
Eduardo - Ortega
Terrence - Wade
Enrique - Estrada
Freddie - Contreras
Wade - Barnett
',
            'locations' => 'US - New York - New York
US - Los Angeles - California
US - Chicago - Illinois
US - Houston - Texas
US - Phoenix - Arizona
US - Philadelphia - Pennsylvania
US - San Antonio - Texas
US - San Diego - California
US - Dallas - Texas
US - San Jose - California
US - Austin - Texas
US - Jacksonville - Florida
US - San Francisco - California
US - Columbus - Ohio
US - Indianapolis - Indiana
US - Fort Worth - Texas
US - Charlotte - North Carolina
US - Seattle - Washington
US - Denver - Colorado
US - El Paso - Texas
US - Washington - District of Columbia
US - Boston - Massachusetts
US - Detroit - Michigan
US - Nashville - Tennessee
US - Memphis - Tennessee
US - Portland - Oregon
US - Oklahoma City - Oklahoma
US - Las Vegas - Nevada
US - Louisville - Kentucky
US - Baltimore - Maryland
US - Milwaukee - Wisconsin
US - Albuquerque - New Mexico
US - Tucson - Arizona
US - Fresno - California
US - Sacramento - California
US - Mesa - Arizona
US - Kansas City - Missouri
US - Atlanta - Georgia
US - Long Beach - California
US - Colorado Springs - Colorado
US - Raleigh - North Carolina
US - Miami - Florida
US - Virginia Beach - Virginia
US - Omaha - Nebraska
US - Oakland - California
US - Minneapolis - Minnesota
US - Tulsa - Oklahoma
US - Arlington - Texas
US - New Orleans - Louisiana
US - Wichita - Kansas
US - Cleveland - Ohio
US - Tampa - Florida
US - Bakersfield - California
US - Aurora - Colorado
US - Honolulu - Hawaii
US - Anaheim - California
US - Santa Ana - California
US - Corpus Christi - Texas
US - Riverside - California
US - Lexington - Kentucky
US - St. Louis - Missouri
US - Stockton - California
US - Pittsburgh - Pennsylvania
US - Saint Paul - Minnesota
US - Cincinnati - Ohio
US - Anchorage - Alaska
US - Henderson - Nevada
US - Greensboro - North Carolina
US - Plano - Texas
US - Newark - New Jersey
US - Lincoln - Nebraska
US - Toledo - Ohio
US - Orlando - Florida
US - Chula Vista - California
US - Irvine - California
US - Fort Wayne - Indiana
US - Jersey City - New Jersey
US - Durham - North Carolina
US - St. Petersburg - Florida
US - Laredo - Texas
US - Buffalo - New York
US - Madison - Wisconsin
US - Lubbock - Texas
US - Chandler - Arizona
US - Scottsdale - Arizona
US - Glendale - Arizona
US - Reno - Nevada
US - Norfolk - Virginia
US - Winston–Salem - North Carolina
US - North Las Vegas - Nevada
US - Irving - Texas
US - Chesapeake - Virginia
US - Gilbert - Arizona
US - Hialeah - Florida
US - Garland - Texas
US - Fremont - California
US - Baton Rouge - Louisiana
US - Richmond - Virginia
US - Boise - Idaho
US - San Bernardino - California
US - Spokane - Washington
US - Des Moines - Iowa
US - Modesto - California
US - Birmingham - Alabama
US - Tacoma - Washington
US - Fontana - California
US - Rochester - New York
US - Oxnard - California
US - Moreno Valley - California
US - Fayetteville - North Carolina
US - Aurora - Illinois
US - Glendale - California
US - Yonkers - New York
US - Huntington Beach - California
US - Montgomery - Alabama
US - Amarillo - Texas
US - Little Rock - Arkansas
US - Akron - Ohio
US - Columbus - Georgia
US - Augusta - Georgia
US - Grand Rapids - Michigan
US - Shreveport - Louisiana
US - Salt Lake City - Utah
US - Huntsville - Alabama
US - Mobile - Alabama
US - Tallahassee - Florida
US - Grand Prairie - Texas
US - Overland Park - Kansas
US - Knoxville - Tennessee
US - Port St. Lucie - Florida
US - Worcester - Massachusetts
US - Brownsville - Texas
US - Tempe - Arizona
US - Santa Clarita - California
US - Newport News - Virginia
US - Cape Coral - Florida
US - Providence - Rhode Island
US - Fort Lauderdale - Florida
US - Chattanooga - Tennessee
US - Rancho Cucamonga - California
US - Oceanside - California
US - Santa Rosa - California
US - Garden Grove - California
US - Vancouver - Washington
US - Sioux Falls - South Dakota
US - Ontario - California
US - McKinney - Texas
US - Elk Grove - California
US - Jackson - Mississippi
US - Pembroke Pines - Florida
US - Salem - Oregon
US - Springfield - Missouri
US - Corona - California
US - Eugene - Oregon
US - Fort Collins - Colorado
US - Peoria - Arizona
US - Frisco - Texas
US - Cary - North Carolina
US - Lancaster - California
US - Hayward - California
US - Palmdale - California
US - Salinas - California
US - Alexandria - Virginia
US - Lakewood - Colorado
US - Springfield - Massachusetts
US - Pasadena - Texas
US - Sunnyvale - California
US - Macon - Georgia
US - Pomona - California
US - Hollywood - Florida
US - Kansas City - Kansas
US - Escondido - California
US - Clarksville - Tennessee
US - Joliet - Illinois
US - Rockford - Illinois
US - Torrance - California
US - Naperville - Illinois
US - Paterson - New Jersey
US - Savannah - Georgia
US - Bridgeport - Connecticut
US - Mesquite - Texas
US - Killeen - Texas
US - Syracuse - New York
US - McAllen - Texas
US - Pasadena - California
US - Bellevue - Washington
US - Fullerton - California
US - Orange - California
US - Dayton - Ohio
US - Miramar - Florida
US - Thornton - Colorado
US - West Valley City - Utah
US - Olathe - Kansas
US - Hampton - Virginia
US - Warren - Michigan
US - Midland - Texas
US - Waco - Texas
US - Charleston - South Carolina
US - Columbia - South Carolina
US - Denton - Texas
US - Carrollton - Texas
US - Surprise - Arizona
US - Roseville - California
US - Sterling Heights - Michigan
US - Murfreesboro - Tennessee
US - Gainesville - Florida
US - Cedar Rapids - Iowa
US - Visalia - California
US - Coral Springs - Florida
US - New Haven - Connecticut
US - Stamford - Connecticut
US - Thousand Oaks - California
US - Concord - California
US - Elizabeth - New Jersey
US - Lafayette - Louisiana
US - Kent - Washington
US - Topeka - Kansas
US - Simi Valley - California
US - Santa Clara - California
US - Athens - Georgia
US - Hartford - Connecticut
US - Victorville - California
US - Abilene - Texas
US - Norman - Oklahoma
US - Vallejo - California
US - Berkeley - California
US - Round Rock - Texas
US - Ann Arbor - Michigan
US - Fargo -  North Dakota
US - Columbia - Missouri
US - Allentown - Pennsylvania
US - Evansville - Indiana
US - Beaumont - Texas
US - Odessa - Texas
US - Wilmington - North Carolina
US - Arvada - Colorado
US - Independence - Missouri
US - Provo - Utah
US - Lansing - Michigan
US - El Monte - California
US - Springfield - Illinois
US - Fairfield - California
US - Clearwater - Florida
US - Peoria - Illinois
US - Rochester - Minnesota
US - Carlsbad - California
US - Westminster - Colorado
US - West Jordan - Utah
US - Pearland - Texas
US - Richardson - Texas
US - Downey - California
US - Miami Gardens - Florida
US - Temecula - California
US - Costa Mesa - California
US - College Station - Texas
US - Elgin - Illinois
US - Murrieta - California
US - Gresham - Oregon
US - High Point - North Carolina
US - Antioch - California
US - Inglewood - California
US - Cambridge - Massachusetts
US - Lowell - Massachusetts
US - Manchester - New Hampshire
US - Billings - Montana
US - Pueblo - Colorado
US - Palm Bay - Florida
US - Centennial - Colorado
US - Richmond - California
US - Ventura - California
US - Pompano Beach - Florida
US - North Charleston - South Carolina
US - Everett - Washington
US - Waterbury - Connecticut
US - West Palm Beach - Florida
US - Boulder - Colorado
US - West Covina - California
US - Broken Arrow - Oklahoma
US - Clovis - California
US - Daly City - California
US - Lakeland - Florida
US - Santa Maria - California
US - Norwalk - California
US - Sandy Springs - Georgia
US - Hillsboro - Oregon
US - Green Bay - Wisconsin
US - Tyler - Texas
US - Wichita Falls - Texas
US - Lewisville - Texas
US - Burbank - California
US - Greeley - Colorado
US - San Mateo - California
US - El Cajon - California
US - Jurupa Valley - California
US - Rialto - California
US - Davenport - Iowa
US - League City - Texas
US - Edison - New Jersey
US - Davie - Florida
US - Las Cruces - New Mexico
US - South Bend - Indiana
US - Vista - California
US - Woodbridge - New Jersey
US - Renton - Washington
US - Lakewood - New Jersey
US - San Angelo - Texas
US - Clinton - Michigan'
        );
    }
}
