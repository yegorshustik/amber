import os
import glob

# 1. Replace all index.html#lead with contacts.html#lead globally
files = glob.glob('src/**/*.html', recursive=True) + glob.glob('src/**/*.njk', recursive=True)

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = content.replace('href="index.html#lead"', 'href="contacts.html#lead"')
    
    # 2. In index.html, replace href="#lead" with href="contacts.html#lead"
    if file.endswith('index.html'):
        new_content = new_content.replace('href="#lead"', 'href="contacts.html#lead"')
        new_content = new_content.replace('href="contacts.html"', 'href="contacts.html#lead"')
        
    # 3. Replace "Book a call" with "Free consultation" globally (mainly in index.html and contacts.html)
    new_content = new_content.replace('>Book a call</a>', '>Free consultation</a>')
    
    if new_content != content:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(new_content)

print("Links and text updated successfully.")
