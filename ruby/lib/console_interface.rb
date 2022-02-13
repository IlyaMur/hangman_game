class ConsoleInterface
  FIGURES =
      Dir["#{__dir__}/../data/figures/*.txt"].
      sort.
      map { |file_name| File.read(file_name) }

  def initialize(game)
    @game = game
  end

  def print_out
    puts <<~END
      #{"Слово: #{word_to_show}".blue}
      #{"#{figure}".yellow}
      #{"Ошибки (#{@game.errors_made}): #{errors_to_show}".red}
      У вас осталось ошибок: #{@game.errors_allowed}
    END

    if @game.won?
      puts "Поздравляем, вы выиграли!".green
    elsif @game.lost?
      puts "Вы проиграли, загаданное слово: #{@game.word}".red
    end
  end

  def figure
    FIGURES[@game.errors_made]
  end

  def word_to_show
    result =
      @game.letters_to_guess.map do |letter|
        if letter == nil
          "__"
        else
          letter
        end
      end

    result.join(" ")
  end

  def errors_to_show
    @game.errors.join(", ")
  end
    
  def greeting
    puts "Всем привет!".green
    puts 'Начинаем игру Виселица!'.green
  end

  def get_input
    print "Введите следующую букву: "
    input = gets
    input.upcase[0]
  end
end
