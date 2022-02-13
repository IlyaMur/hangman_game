require 'colorize'
require_relative 'lib/console_interface'
require_relative 'lib/game'

word = File.readlines(__dir__ + '/data/words.txt', encoding: 'UTF-8', chomp: true).sample
game = Game.new(word)
console_interface = ConsoleInterface.new(game)

console_interface.greeting

until game.over?
  console_interface.print_out
  letter = console_interface.get_input
  game.play!(letter)
end

console_interface.print_out
