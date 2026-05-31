<?php

namespace App\Filament\Resources\SentMails\Pages;

use App\Filament\Resources\SentMails\SentMailResource;
use Filament\Resources\Pages\ListRecords;

class ListSentMails extends ListRecords
{
    protected static string $resource = SentMailResource::class;
}
