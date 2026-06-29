<?php
/**
 * Value object representing a plugin asset.
 *
 * @package GSAP_Elementor_Toolkit\Core
 */

namespace GSAP_Elementor_Toolkit\Core;

/**
 * Encapsulates the definition of a frontend or admin asset.
 */
class Asset {
	/**
	 * Style asset type.
	 */
	public const TYPE_STYLE = 'style';

	/**
	 * Script asset type.
	 */
	public const TYPE_SCRIPT = 'script';

	/**
	 * Frontend asset area.
	 */
	public const AREA_FRONTEND = 'frontend';

	/**
	 * Admin asset area.
	 */
	public const AREA_ADMIN = 'admin';

	/**
	 * Asset handle.
	 *
	 * @var string
	 */
	private string $handle;

	/**
	 * Asset source URL or path.
	 *
	 * @var string
	 */
	private string $source;

	/**
	 * Asset type.
	 *
	 * @var string
	 */
	private string $type;

	/**
	 * Asset area.
	 *
	 * @var string
	 */
	private string $area;

	/**
	 * Asset dependencies.
	 *
	 * @var array<int, string>
	 */
	private array $dependencies;

	/**
	 * Asset version.
	 *
	 * @var string
	 */
	private string $version;

	/**
	 * Whether the script should load in the footer.
	 *
	 * @var bool
	 */
	private bool $footer;

	/**
	 * Future strategy placeholder.
	 *
	 * @var string
	 */
	private string $strategy;

	/**
	 * Create a new asset definition.
	 *
	 * @param string $handle Asset handle.
	 * @param string $source Asset source.
	 * @param string $type Asset type.
	 * @param string $area Asset area.
	 * @param array<int, string> $dependencies Asset dependencies.
	 * @param string $version Asset version.
	 * @param bool $footer Whether the script should load in the footer.
	 * @param string $strategy Future loading strategy.
	 */
	public function __construct(
		string $handle,
		string $source,
		string $type,
		string $area,
		array $dependencies = array(),
		string $version = '',
		bool $footer = false,
		string $strategy = 'default'
	) {
		$this->handle      = $handle;
		$this->source      = $source;
		$this->type        = $type;
		$this->area        = $area;
		$this->dependencies = $dependencies;
		$this->version     = $version;
		$this->footer      = $footer;
		$this->strategy    = $strategy;
	}

	/**
	 * Return the asset handle.
	 *
	 * @return string
	 */
	public function getHandle(): string {
		return $this->handle;
	}

	/**
	 * Return the asset source.
	 *
	 * @return string
	 */
	public function getSource(): string {
		return $this->source;
	}

	/**
	 * Return the asset dependencies.
	 *
	 * @return array<int, string>
	 */
	public function getDependencies(): array {
		return $this->dependencies;
	}

	/**
	 * Return the asset version.
	 *
	 * @return string
	 */
	public function getVersion(): string {
		return $this->version;
	}

	/**
	 * Return the asset type.
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Return the asset area.
	 *
	 * @return string
	 */
	public function getArea(): string {
		return $this->area;
	}

	/**
	 * Return whether the asset should load in the footer.
	 *
	 * @return bool
	 */
	public function isFooter(): bool {
		return $this->footer;
	}

	/**
	 * Return the asset strategy.
	 *
	 * @return string
	 */
	public function getStrategy(): string {
		return $this->strategy;
	}
}
