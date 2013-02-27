def handle_match(match)
  bits = match.split '/'
  plugin = bits.shift(2) if bits[0] === 'Plugin'
  type = plugin.nil? ? 'app' : plugin[1]
  if bits[0] === 'Test'
    return if bits[1] === 'Fixture'
    bits.slice!(0, 2)
    bits[bits.length - 1] = bits.last.gsub('Test', '')
  end
  file = bits.join '/'
  run_test file, type if bits.last.match /^([A-Z]){1}([A-Za-z])+/
end

def run_test(file, plugin)
  clear_screen
  result = `Console/cake test #{plugin} #{file}`
  notify result
  puts result
end

def clear_screen
  puts "\e[H\e[2J"
end

def notify(result)
  group = "GuardAutoTest"
  title = 'Guard AutoTest'
  app = 'com.apple.Terminal'
  notify = if result.match /OK/
    title = 'Pass - ' + title
    message = 'All run tests pass!'
    true
  elsif result.match /FAILURES!/
    title = 'Fail - ' + title
    message = 'There was a failing test.'
    true
  else
    false
  end

  if notify
    `terminal-notifier -message '#{message}' -title '#{title}' -group '#{group}' -activate '#{app}'`
  end
end

guard 'shell' do
  watch(/(.*)\.php/) do |match|
    handle_match match[1]
  end
end
