<?php
declare(strict_types=1);

namespace Bilbofox\PHPDIPanel;

use DI\Container;
use Tracy;
use Tracy\Debugger;

class ContainerPanel implements Tracy\IBarPanel
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

// -----------------------------------------------------------------------------
    // ~Tracy\IBarPanel

    public function getTab()
    {
        return Tracy\Helpers::capture(function (): void {
            require __DIR__ . '/templates/ContainerPanel.tab.phtml';
        });
    }


    /**
     * Renders panel.
     */
    public function getPanel()
    {
        return Tracy\Helpers::capture(function (): void {
            $knownEntryNames = array_fill_keys($this->container->getKnownEntryNames(), null);
            $resolvedEntries = (fn(): array => $this->resolvedEntries)->bindTo($this->container, Container::class)();

            $services = $resolvedEntries + $knownEntryNames;
            ksort($services);
            require __DIR__ . '/templates/ContainerPanel.panel.phtml';
        });
    }
}