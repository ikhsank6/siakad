import re
import sys

with open(sys.argv[1], 'r') as f:
    content = f.read()

directives = ['if', 'section', 'push', 'auth', 'guest', 'foreach', 'for', 'while', 'can', 'cannot', 'component', 'slot']
for d in directives:
    start = len(re.findall(f'@{d}\\b', content))
    end = len(re.findall(f'@end{d}\\b', content))
    if start != end:
        print(f"Mismatch in @{d}: {start} starts, {end} ends")

# Special check for @if with @else/@elseif
if_count = len(re.findall(r'@if\b', content))
endif_count = len(re.findall(r'@endif\b', content))
if if_count != endif_count:
    print(f"Blade @if mismatch: {if_count} vs {endif_count}")
