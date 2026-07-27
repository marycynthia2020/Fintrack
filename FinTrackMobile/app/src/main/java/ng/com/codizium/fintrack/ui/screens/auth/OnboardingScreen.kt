package ng.com.codizium.fintrack.ui.screens.auth

import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.ArrowBack
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.navigation.Routes
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun OnboardingScreen(navController: NavController) {
    var selectedOption by remember { mutableStateOf(0) }

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray)
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Row(modifier = Modifier.fillMaxWidth()) {
                IconButton(onClick = { navController.popBackStack() }) {
                    Icon(Icons.Filled.ArrowBack, null, tint = TextPrimary)
                }
            }

            Spacer(Modifier.height(8.dp))

            // Illustration placeholder
            Box(
                modifier = Modifier
                    .size(120.dp)
                    .clip(RoundedCornerShape(16.dp))
                    .background(FinTrackBlueSurface),
                contentAlignment = Alignment.Center
            ) {
                Icon(Icons.Filled.Home, null, tint = FinTrackBlue, modifier = Modifier.size(60.dp))
            }

            Spacer(Modifier.height(24.dp))

            Text(
                "Create your account",
                fontSize = 22.sp,
                fontWeight = FontWeight.Bold,
                color = TextPrimary,
                textAlign = TextAlign.Center
            )
            Spacer(Modifier.height(8.dp))
            Text(
                "Choose how to continue",
                fontSize = 14.sp,
                color = TextSecondary,
                textAlign = TextAlign.Center
            )

            Spacer(Modifier.height(32.dp))

            // Option 1 — Create new org
            OrgOptionCard(
                selected = selectedOption == 0,
                icon = { Icon(Icons.Filled.Home, null, tint = if (selectedOption == 0) FinTrackBlue else TextSecondary) },
                title = "Create a new organization",
                subtitle = "You'll be the owner of a new organization.",
                onClick = { selectedOption = 0 }
            )

            Spacer(Modifier.height(12.dp))

            // Option 2 — Join existing org
            OrgOptionCard(
                selected = selectedOption == 1,
                icon = { Icon(Icons.Filled.Person, null, tint = if (selectedOption == 1) FinTrackBlue else TextSecondary) },
                title = "Join an existing organization",
                subtitle = "You have an invite code or email invite.",
                onClick = { selectedOption = 1 }
            )

            Spacer(Modifier.height(32.dp))

            Button(
                onClick = {
                    if (selectedOption == 0) navController.navigate(Routes.CREATE_ORG)
                    else navController.navigate(Routes.DASHBOARD) {
                        popUpTo(Routes.LOGIN) { inclusive = true }
                    }
                },
                modifier = Modifier.fillMaxWidth().height(48.dp),
                shape = RoundedCornerShape(10.dp),
                colors = ButtonDefaults.buttonColors(containerColor = FinTrackBlue)
            ) {
                Text("Continue", fontSize = 15.sp, fontWeight = FontWeight.SemiBold)
            }

            Spacer(Modifier.height(20.dp))

            Row(verticalAlignment = Alignment.CenterVertically) {
                Text("Already have an account?", fontSize = 13.sp, color = TextSecondary)
                TextButton(onClick = { navController.navigate(Routes.LOGIN) {
                    popUpTo(Routes.LOGIN) { inclusive = true }
                }}) {
                    Text("Sign in", fontSize = 13.sp, color = FinTrackBlue, fontWeight = FontWeight.SemiBold)
                }
            }
        }
    }
}

@Composable
private fun OrgOptionCard(
    selected: Boolean,
    icon: @Composable () -> Unit,
    title: String,
    subtitle: String,
    onClick: () -> Unit
) {
    val borderColor = if (selected) FinTrackBlue else BorderGray
    val bgColor = if (selected) FinTrackBlueSurface else CardWhite

    Row(
        modifier = Modifier
            .fillMaxWidth()
            .clip(RoundedCornerShape(12.dp))
            .background(bgColor)
            .border(1.5.dp, borderColor, RoundedCornerShape(12.dp))
            .clickable(onClick = onClick)
            .padding(16.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        Box(
            modifier = Modifier
                .size(40.dp)
                .clip(RoundedCornerShape(8.dp))
                .background(if (selected) FinTrackBlue.copy(alpha = 0.1f) else SurfaceGray),
            contentAlignment = Alignment.Center
        ) {
            icon()
        }
        Spacer(Modifier.width(12.dp))
        Column(modifier = Modifier.weight(1f)) {
            Text(title, fontSize = 14.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
            Text(subtitle, fontSize = 12.sp, color = TextSecondary)
        }
        RadioButton(
            selected = selected,
            onClick = onClick,
            colors = RadioButtonDefaults.colors(selectedColor = FinTrackBlue)
        )
    }
}
