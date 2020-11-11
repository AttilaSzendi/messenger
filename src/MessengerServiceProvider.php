<?php

namespace Stilldesign\Messenger;

use Illuminate\Support\ServiceProvider;
use Stilldesign\Messenger\Contracts\ConversationListTransformerInterface;
use Stilldesign\Messenger\Contracts\ConversationMessageSearchHandlerInterface;
use Stilldesign\Messenger\Contracts\ConversationMessageSearchListTransformerInterface;
use Stilldesign\Messenger\Contracts\ConversationTransformerInterface;
use Stilldesign\Messenger\Contracts\DocumentMessageRequestTransformerInterface;
use Stilldesign\Messenger\Contracts\FileExtensionManagerInterface;
use Stilldesign\Messenger\Contracts\FileUploadHandlerInterface;
use Stilldesign\Messenger\Contracts\ImageMessageRequestTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageCreateHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageCreateResponseHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageEventsHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageListTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageRepositoryInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;
use Stilldesign\Messenger\Contracts\UserRepositoryInterface;
use Stilldesign\Messenger\Handlers\ConversationCreateHandler;
use Stilldesign\Messenger\Handlers\ConversationMessageSearchHandler;
use Stilldesign\Messenger\Handlers\FileUploadHandler;
use Stilldesign\Messenger\Handlers\MessageCreateHandler;
use Stilldesign\Messenger\Handlers\MessageCreateResponseHandler;
use Stilldesign\Messenger\Handlers\MessageEventsHandler;
use Stilldesign\Messenger\Http\Requests\DocumentMessageRequest;
use Stilldesign\Messenger\Http\Requests\ImageMessageRequest;
use Stilldesign\Messenger\Models\Message;
use Stilldesign\Messenger\Observers\MessageObserver;
use Stilldesign\Messenger\Repositories\ConversationRepository;
use Stilldesign\Messenger\Repositories\MessageRepository;
use Stilldesign\Messenger\Repositories\UserRepository;
use Stilldesign\Messenger\Transformers\ConversationListTransformer;
use Stilldesign\Messenger\Transformers\ConversationMessageSearchListTransformer;
use Stilldesign\Messenger\Transformers\ConversationTransformer;
use Stilldesign\Messenger\Transformers\DocumentMessageRequestTransformer;
use Stilldesign\Messenger\Transformers\ImageMessageRequestTransformer;
use Stilldesign\Messenger\Transformers\MessageListTransformer;
use Stilldesign\Messenger\Transformers\MessageRequestTransformer;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Contracts\ConversationCreateHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageRequestTransformerInterface;
use Stilldesign\Messenger\Transformers\MessageTransformer;
use Stilldesign\Messenger\Utils\FileExtensions\DocumentExtensionManager;
use Stilldesign\Messenger\Utils\FileExtensions\ImageExtensionManager;

class MessengerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Message::observe(MessageObserver::class);

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('/migrations'),
            __DIR__.'/config/messengerAllowedFiles.php' => config_path('messengerAllowedFiles.php'),
            __DIR__.'/config/messenger.php' => config_path('messenger.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ConversationRepositoryInterface::class,
            ConversationRepository::class
        );

        $this->app->bind(
            ConversationCreateHandlerInterface::class,
            ConversationCreateHandler::class
        );

        $this->app->bind(
            ImageMessageRequestTransformerInterface::class,
            ImageMessageRequestTransformer::class
        );

        $this->app->bind(
            DocumentMessageRequestTransformerInterface::class,
            DocumentMessageRequestTransformer::class
        );

        $this->app->bind(
            MessageRequestTransformerInterface::class,
            MessageRequestTransformer::class
        );

        $this->app->bind(
            MessageListTransformerInterface::class,
            MessageListTransformer::class
        );

        $this->app->bind(
            FileUploadHandlerInterface::class,
            FileUploadHandler::class
        );

        $this->app->bind(
            MessageTransformerInterface::class,
            MessageTransformer::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            MessageRepositoryInterface::class,
            MessageRepository::class
        );

        $this->app->bind(
            ConversationListTransformerInterface::class,
            ConversationListTransformer::class
        );

        $this->app->bind(
            ConversationTransformerInterface::class,
            ConversationTransformer::class
        );

        $this->app->bind(
            MessageCreateHandlerInterface::class,
            MessageCreateHandler::class
        );

        $this->app->bind(
            MessageEventsHandlerInterface::class,
            MessageEventsHandler::class
        );

        $this->app->bind(
            MessageCreateResponseHandlerInterface::class,
            MessageCreateResponseHandler::class
        );

        $this->app->when(ImageMessageRequest::class)
            ->needs(FileExtensionManagerInterface::class)
            ->give(ImageExtensionManager::class);

        $this->app->when(DocumentMessageRequest::class)
            ->needs(FileExtensionManagerInterface::class)
            ->give(DocumentExtensionManager::class);

        $this->app->bind(
            ConversationMessageSearchHandlerInterface::class,
            ConversationMessageSearchHandler::class
        );

        $this->app->bind(
            ConversationMessageSearchListTransformerInterface::class,
            ConversationMessageSearchListTransformer::class
        );
    }
}
