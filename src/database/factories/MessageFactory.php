<?php

/**
 * @var $factory Illuminate\Database\Eloquent\Factory
 */
$factory->define(\Stilldesign\Messenger\Models\Message::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->sentence,
        'conversation_id' => function () {
            return factory(\Stilldesign\Messenger\Models\Conversation::class)->create()->id;
        },
        'user_id' => function () {
            return factory(config('messenger.user'))->create()->id;
        },
        'ip_address' => $faker->ipv4
    ];
});
