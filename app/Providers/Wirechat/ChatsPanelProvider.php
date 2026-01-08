<?php

namespace App\Providers\Wirechat;

use Wirechat\Wirechat\Panel;
use Wirechat\Wirechat\PanelProvider;
use Wirechat\Wirechat\Support\Color;

class ChatsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('chats')
            ->path('chats')
            ->middleware(['web', 'auth'])
            ->createChatAction()
            ->createGroupAction()
            ->deleteChatAction()
            ->clearChatAction()
            ->attachments()
            ->default();
    }
}
