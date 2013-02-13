#!/usr/bin/perl


print <<EOF
<!DOCTYPE HTML>
<html>
<head>
   <title>Requirement Documents</title>
   <meta charset="UTF-8" />
   <style>
       html { font-family: "Times New Roman", Garamond, Georgia, serif; }
       body { max-width: 38em; margin: auto; text-align: justify; }
       h1 { text-align: center; }
       h2 { font-variant: small-caps; }
       /* So the preformatted text doesn't fall of the page. */
       pre { white-space: pre-wrap; }
   </style>
</head>
<body>
EOF
;

print while (<>);

print <<EOF
</body>
</html>
EOF
;

=head1 NAME

5iver -- Wrap an HTML 5 snippet into a full HTML 5 page.

=head1 SYNOPSIS

5iver < F<html-snippet> > F<html>

=cut
