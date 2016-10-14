# Magicbot

## What's this?

Magicbot is a simple slack bot that answer to `/mtg <card name>` requests.

## How's it work?

It uses slack's slash command system, and is simply a php script that takes a few POST parameters and echoes a JSON string if it finds any matching cards.

## What more do I need to know?

I've no idea. Wait, the format for the cards.txt file. I guess it's public knowledge, so I could legally upload it, but I'd rather not be in a gray zone, so I'll just not.

cards.txt is a simple list of cards on the format

`<multiverse id>\t<set code>\t<card name>`

If you're really going to use this and you encounter any problems, feel free to bug me here.