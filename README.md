![Code-sniffer-PSR-12](https://github.com/ilyamur/hangman_game/actions/workflows/codesniffer-lint.yml/badge.svg)
![PHPUnit-Tests](https://github.com/ilyamur/hangman_game/actions/workflows/unit-tests.yml/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/d9d6eb7f1e4a7f4c5d3e/maintainability)](https://codeclimate.com/github/IlyaMur/hangman_game/maintainability)

# Игра Hangman ("Виселица")

CLI-версия популярной настольной игры  ["Виселица"](https://ru.wikipedia.org/wiki/Виселица_(игра)), написанная на PHP.  
Игрок предлагая буквы, пытается угадать загаданное слово.  
Доступен красочный интерфейс, таблица рекордов и расширяемый словарь.  

Игра написана для души и распространяется свободно.

```console
Слово: К О __ О __ __
          _______
          |/
          |     ( )
          |      |
          |
          |
          |
          |
          |
        __|________
        |         |

Ошибки 2: Ч, У
У вас осталось ошибок: 4

Введите следующую букву:
```

## Установка и запуск

`PHP >= 8.0`  
Необходимо склонировать репозиторий

    $ git clone https://github.com/IlyaMur/hangman_game.git  
    $ cd hangman_game

Установить зависимости

    $ make install

Тесты

    $ make test

Запустить игру

    $ ./bin/hangman


## Вывод таблицы рекордов

Для печати таблицы рекордов необходимо передать исполняемому файлу аргумент в формате:  
`top:{число}`.  
Доступна выборка от 1 до 10 игроков.

    $ ./bin/hangman top:5

Для печати результатов конкретного игрока неоходимо передать имя в формате:     
`name:{имя}`

    $ ./bin/hangman name:Ilya

## Словарь

По пути `database/hangman.db` доступна база SQLite, таблица `words` отвечает за игровой словарь.

## Демонстрация игры

Пример игрового процесса:  
[![asciicast](https://asciinema.org/a/fCD7RcXZKIGs9BqWjkacloLDE.svg)](https://asciinema.org/a/fCD7RcXZKIGs9BqWjkacloLDE)

Вывод таблицы рекордов:  
[![asciicast](https://asciinema.org/a/FZpvlkH6mwJQ1W8Nc4TLuM7Yf.svg)](https://asciinema.org/a/FZpvlkH6mwJQ1W8Nc4TLuM7Yf)
