class Game
  TOTAL_ERRORS_ALLOWED = 7

  def initialize(word)
    @letters = word.chars
    @user_guesses = []
  end

  def errors
    @user_guesses - normalized_letters(@letters)
  end

  def errors_made
    errors.length
  end

  def errors_allowed
    TOTAL_ERRORS_ALLOWED - errors_made
  end

  def letters_to_guess
    result =
      @letters.map do |letter|
        if @user_guesses.include?(normalize_letter(letter))
          letter
        else
          nil
        end
      end
    result
  end

  def normalize_letter(letter)
    if letter == 'Ё'
      return 'Е'
    elsif letter == 'Й'
      return 'И'
    end
    letter
  end

  def normalized_letters(letters)
    letters.map { |c| normalize_letter(c) }
  end

  def lost?
    errors_allowed == 0
  end

  def over?
    won? || lost?
  end

  def play!(letter)
    normalize_letter = normalize_letter(letter)

    if !over? && !@user_guesses.include?(normalize_letter)
      @user_guesses << normalize_letter
    end
  end

  def won?
    (normalized_letters(@letters) - @user_guesses).empty?
  end

  def word
    @letters.join
  end
end
