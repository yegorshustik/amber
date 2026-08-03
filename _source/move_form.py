import re

# --- 1. Process index.html ---
with open('src/index.html', 'r', encoding='utf-8') as f:
    idx_content = f.read()

# Extract JS block
js_start_marker = "/* ---- Date-of-birth mask: dd.mm.yyyy ---- */"
js_end_marker = "io.observe(nameEl);\n})();"
js_pattern = re.compile(re.escape(js_start_marker) + r".*?" + re.escape(js_end_marker), re.DOTALL)
js_match = js_pattern.search(idx_content)
extracted_js = js_match.group(0)

# Remove extracted JS from index.html
idx_content = idx_content.replace(extracted_js, "")
# clean up any leftover empty lines in script block
idx_content = re.sub(r'\n\s*\n\s*</script>', '\n</script>', idx_content)

# Extract LEAD CAPTURE section
lead_pattern = re.compile(r'  <!-- ============ LEAD CAPTURE.*?</section>\n', re.DOTALL)
lead_match = lead_pattern.search(idx_content)
extracted_lead_html = lead_match.group(0)

# Extract PRICING section
pricing_pattern = re.compile(r'  <!-- ============ PRICING.*?</section>\n', re.DOTALL)
pricing_match = pricing_pattern.search(idx_content)
extracted_pricing_html = pricing_match.group(0)

# Remove both sections from their current places
idx_content = idx_content.replace(extracted_lead_html, "")
idx_content = idx_content.replace(extracted_pricing_html, "")

# Modify the PRICING section
modified_pricing_html = extracted_pricing_html.replace('href="#lead"', 'href="contacts.html"')
modified_pricing_html = modified_pricing_html.replace('Free consultation', 'Book a call')

# Append PRICING section to the end of index.html
idx_content = idx_content.rstrip() + "\n\n" + modified_pricing_html

with open('src/index.html', 'w', encoding='utf-8') as f:
    f.write(idx_content)


# --- 2. Process contacts.html ---
with open('src/contacts.html', 'r', encoding='utf-8') as f:
    cnt_content = f.read()

# Inject JS into contacts.html
# The extra_js ends with:
#     });
#   });
# })();
# </script>
# We will insert it before </script>
insert_pos = cnt_content.find("</script>\n{% endset %}")
if insert_pos != -1:
    cnt_content = cnt_content[:insert_pos] + "\n" + extracted_js + "\n" + cnt_content[insert_pos:]
else:
    print("Warning: Could not find extra_js insertion point in contacts.html")

# Modify contacts.html "Ready to start?" button
cnt_content = cnt_content.replace('href="index.html#lead"', 'href="#lead"')
cnt_content = cnt_content.replace('>Book a free consultation</a>', '>Book a call</a>')

# Append LEAD CAPTURE to contacts.html
cnt_content = cnt_content.rstrip() + "\n\n" + extracted_lead_html

with open('src/contacts.html', 'w', encoding='utf-8') as f:
    f.write(cnt_content)

print("Done successfully.")
