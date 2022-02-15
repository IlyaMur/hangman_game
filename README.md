![Code-sniffer-PSR-12](https://github.com/ilyamur/hangman_game/actions/workflows/codesniffer-lint.yml/badge.svg)
![PHPUnit-Tests](https://github.com/ilyamur/hangman_game/actions/workflows/unit-tests.yml/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/d9d6eb7f1e4a7f4c5d3e/maintainability)](https://codeclimate.com/github/IlyaMur/hangman_game/maintainability)

# Игра Hangman ("Виселица")

CLI-версия популярной настольной игры  ["Виселица"](https://ru.wikipedia.org/wiki/Висилица_(игра)), написанная на PHP.  
Игрок предлагая буквы, пытается угадать загаданное слово.  
Предлагается красочный интерфейс, таблица рекордов и расширяемый словарь слов.  

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
У вас осталось ошибок: 5

Введите следующую букву:
```

## Установка и запуск

`PHP >= 8.0`  
Необходимо склонировать репозиторий

    $ git clone https://github.com/IlyaMur/hangman_game.git  
    $ cd hangman_game

Установить зависимости

    $ make install

Запустить тесты

    $ make test

Запустить игру

    $ ./bin/hangman


## Вывод таблицы рекордов

Для печати таблицы рекордов необходимо передать запускаемому файлу следующий аргумент

    $ ./bin/hangman top:5

На экран будет распечатана таблица с результатами 5 лучших игроков. Доступна выборка от 1 до 10 игроков.

Для печати результатов конкретного игрока

    $ ./bin/hangman name:Ilya

Распечатает результаты игрока под именем Ilya.

## Словарь

Для редактирования по пути `database/hangman.db` доступна база SQLite, таблица `words` отвечает за игровой словарь.

## Демонстрация игры

Пример игры:  
[![asciicast](https://asciinema.org/a/5ApbdL6O3V3LUuR8qrlZ1J1Xp.svg)](https://asciinema.org/a/5ApbdL6O3V3LUuR8qrlZ1J1Xp)

Вывод таблицы результатов:  
[![asciicast](https://asciinema.org/a/FZpvlkH6mwJQ1W8Nc4TLuM7Yf.svg)](https://asciinema.org/a/FZpvlkH6mwJQ1W8Nc4TLuM7Yf)
