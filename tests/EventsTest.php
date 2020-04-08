<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use app\Models\EventManager;
final class EventsTest extends TestCase
{
    public static function setUpBeforeClass(): void{
        //Run bootstrapper to bring all our happy code together.
        require "bootstrap/Bootstrapper.php";
        $bootstrap = new \bootstrap\Bootstrapper();
        $bootstrap->autoload();
    }
    public function testEventsCanBePulled(): void
    {
        $events = new EventManager();
        $this->assertNotNull($events->getEvents());
    }
    public function testEventsAreJSON(): void {
        $events = new EventManager();
        $this->assertJson($events->getEvents());
    }
    public function testEventCanBePulled(): void
    {
        $events = new EventManager();
        $this->assertNotNull($events->getEvent(1));
    }
    public function testEventAreJSON(): void {
        $events = new EventManager();
        $this->assertJson($events->getEvent(1));
    }
    public function testEventCategoriesCanBePulled(): void{
        $events = new EventManager();
        $this->assertNotNull($events->getEventCategories());
    }
    public function testParticipantsAreJSON(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventParticipants(1));
    }
    public function testOrganisersCanBePulled(): void{
        $events = new EventManager();
        $this->assertNotNull($events->getEventOrganisers(1));
    }
    public function testOrganisersAreJSON(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventOrganisers(1));
    }
    public function testEventSchoolCanBePulled(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventHostingSchool(1));
    }
    public function testEventSchoolIsJSON(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventHostingSchool(1));
    }
    public function testEventLocationCanBePulled(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventLocation(1));
    }
    public function testEventLocationIsJSON(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventLocation(1));
    }
    public function testEventSchoolsCanBePulled(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventSchools());
    }
    public function testEventSchoolsIsJSON(): void{
        $events = new EventManager();
        $this->assertJSON($events->getEventSchools());
    }
}