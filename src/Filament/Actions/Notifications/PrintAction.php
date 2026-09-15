<?php

namespace TomatoPHP\FilamentDocs\Filament\Actions\Notifications;

use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Js;
use Livewire\Component as Livewire;

class PrintAction extends Action
{
    protected Closure | string $route;

    protected Closure | string | null $title = null;

    public static function getDefaultName(): ?string
    {
        return 'print';
    }

    public function route(Closure | string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute()
    {
        return $this->evaluate($this->route);
    }

    public function title(Closure | string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->evaluate($this->title);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(trans('filament-docs::messages.documents.actions.print'))
            ->icon('heroicon-o-printer')
            ->action(function (Livewire $livewire) {
                $title = Js::from((string) $this->getTitle());
                $route = Js::from((string) $this->getRoute());

                $livewire->js(
                    <<<JS
                        let iframe = document.createElement('iframe');
                        let orginalTitle = document.title;
                        let title = {$title};
                        if (title) {
                            document.title = title;
                        }
                        iframe.src = {$route};
                        iframe.style.display = 'none';
                        document.body.appendChild(iframe);
                        iframe.onload = function () {
                            iframe.contentWindow.print();
                            setTimeout(() => {
                                document.body.removeChild(iframe);
                                document.title = orginalTitle;
                            }, 1000);
                        };
                        JS
                );
            });
    }
}
