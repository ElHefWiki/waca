<?php
namespace Waca\Tests;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use ReflectionProperty;
use Waca\DataObjects\User;
use Waca\IdentificationVerifier;
use Waca\Security\SecurityConfiguration;

/**
 * Class SecurityConfigurationTest
 * @package  Waca\Tests
 * @category Security-Critical
 */
class SecurityConfigurationTest extends PHPUnit_Framework_TestCase
{
	/** @var User|PHPUnit_Framework_MockObject_MockObject */
	private $user;
	/** @var IdentificationVerifier|PHPUnit_Framework_MockObject_MockObject */
	private $identificationVerifier;

	public function setUp()
	{
		// for now...
		// @todo fix me please!
		global $forceIdentification;
		$forceIdentification = 0;

		$this->user = $this->getMockBuilder(User::class)->getMock();

		$this->identificationVerifier = $this->getMockBuilder(IdentificationVerifier::class)
			->disableOriginalConstructor()
			->getMock();

		// @todo write tests involving this!
		$this->identificationVerifier->method('isUserIdentified')->willReturn(true);
	}

	public function testAllowsAdmin()
	{
		$this->user->method('isAdmin')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setAdmin(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsUser()
	{
		$this->user->method('isUser')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setUser(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCheckuser()
	{
		$this->user->method('isCheckuser')->willReturn(true);

		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsDeclined()
	{
		$this->user->method('isDeclined')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setDeclined(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsSuspended()
	{
		$this->user->method('isSuspended')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setSuspended(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsNew()
	{
		$this->user->method('isNew')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setNew(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCommunity()
	{
		$this->user->method('isCommunityUser')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setCommunity(SecurityConfiguration::ALLOW);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsAdminWithNonApplicableDeny()
	{
		$this->user->method('isAdmin')->willReturn(true);

		$config = new SecurityConfiguration();
		$config->setAdmin(SecurityConfiguration::ALLOW)->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsUserWithNonApplicableDeny()
	{
		$this->user->method('isUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setUser(SecurityConfiguration::ALLOW)->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCheckuserWithNonApplicableDeny()
	{
		$this->user->method('isCheckuser')->willReturn(true);
		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$config->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsDeclinedWithNonApplicableDeny()
	{
		$this->user->method('isDeclined')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setDeclined(SecurityConfiguration::ALLOW)->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsSuspendedWithNonApplicableDeny()
	{
		$this->user->method('isSuspended')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setSuspended(SecurityConfiguration::ALLOW)->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsNewWithNonApplicableDeny()
	{
		$this->user->method('isNew')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setNew(SecurityConfiguration::ALLOW)->setAdmin(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCommunityWithNonApplicableDeny()
	{
		$this->user->method('isCommunityUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setCommunity(SecurityConfiguration::ALLOW)->setNew(SecurityConfiguration::DENY);
		$this->assertTrue($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsAdminWithApplicableDeny()
	{
		$this->user->method('isAdmin')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setAdmin(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsUserWithApplicableDeny()
	{
		$this->user->method('isUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setUser(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCheckuserWithApplicableDeny()
	{
		$this->user->method('isCheckuser')->willReturn(true);
		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::DENY);

		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsDeclinedWithApplicableDeny()
	{
		$this->user->method('isDeclined')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setDeclined(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsSuspendedWithApplicableDeny()
	{
		$this->user->method('isSuspended')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setSuspended(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsNewWithApplicableDeny()
	{
		$this->user->method('isNew')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setNew(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCommunityWithApplicableDeny()
	{
		$this->user->method('isCommunityUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$config->setCommunity(SecurityConfiguration::DENY);
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsAdminWithDefault()
	{
		$this->user->method('isAdmin')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsUserWithDefault()
	{
		$this->user->method('isUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCheckuserWithDefault()
	{
		$this->user->method('isCheckuser')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsDeclinedWithDefault()
	{
		$this->user->method('isDeclined')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsSuspendedWithDefault()
	{
		$this->user->method('isSuspended')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsNewWithDefault()
	{
		$this->user->method('isNew')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testAllowsCommunityWithDefault()
	{
		$this->user->method('isCommunityUser')->willReturn(true);
		$config = new SecurityConfiguration();
		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testCheckuserAnonymousBypass()
	{
		// This should never happen, but putting a test in to ensure we handle it correctly!
		$this->user->method('isCommunityUser')->willReturn(true);
		$this->user->method('isCheckuser')->willReturn(true);

		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$config->setCommunity(SecurityConfiguration::DENY);

		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testCheckuserSuspendedBypass()
	{
		$this->user->method('isSuspended')->willReturn(true);
		$this->user->method('isCheckuser')->willReturn(true);

		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$config->setSuspended(SecurityConfiguration::DENY);

		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testCheckuserDeclinedBypass()
	{
		$this->user->method('isDeclined')->willReturn(true);
		$this->user->method('isCheckuser')->willReturn(true);

		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$config->setDeclined(SecurityConfiguration::DENY);

		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testCheckuserNewBypass()
	{
		$this->user->method('isNew')->willReturn(true);
		$this->user->method('isCheckuser')->willReturn(true);

		$config = new SecurityConfiguration();

		// set checkuser using reflection
		$reflector = new ReflectionProperty(SecurityConfiguration::class, 'checkuser');
		$reflector->setAccessible(true);
		$reflector->setValue($config, SecurityConfiguration::ALLOW);

		$config->setNew(SecurityConfiguration::DENY);

		$this->assertFalse($config->allows($this->user, $this->identificationVerifier));
	}

	public function testIdentification()
	{
		$this->markTestIncomplete("Please implement me!");
	}
}