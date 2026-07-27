package ng.com.codizium.fintrack.ui.screens.members

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Add
import androidx.compose.material.icons.filled.MoreVert
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.data.model.*
import ng.com.codizium.fintrack.navigation.Routes
import ng.com.codizium.fintrack.ui.screens.dashboard.MainTopBar
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun MembersScreen(navController: NavController) {
    val members = MockData.members

    LazyColumn(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray),
        contentPadding = PaddingValues(bottom = 16.dp)
    ) {
        item { MainTopBar() }

        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp, vertical = 16.dp),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text("Members", fontSize = 20.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
                Button(
                    onClick = { navController.navigate(Routes.INVITE_MEMBER) },
                    shape = RoundedCornerShape(8.dp),
                    colors = ButtonDefaults.buttonColors(containerColor = FinTrackBlue),
                    contentPadding = PaddingValues(horizontal = 12.dp, vertical = 8.dp)
                ) {
                    Icon(Icons.Filled.Add, null, modifier = Modifier.size(16.dp))
                    Spacer(Modifier.width(4.dp))
                    Text("Invite Member", fontSize = 13.sp)
                }
            }
        }

        item {
            // Table header
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp)
                    .clip(RoundedCornerShape(topStart = 10.dp, topEnd = 10.dp))
                    .background(SurfaceGray)
                    .padding(horizontal = 12.dp, vertical = 10.dp)
            ) {
                Text("Name", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1.5f))
                Text("Email", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(2f))
                Text("Role", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1f))
                Text("Joined At", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1.2f))
                Spacer(Modifier.width(24.dp))
            }
        }

        items(members) { member ->
            MemberRow(member)
        }

        item {
            Spacer(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(12.dp)
                    .padding(horizontal = 16.dp)
                    .clip(RoundedCornerShape(bottomStart = 10.dp, bottomEnd = 10.dp))
                    .background(CardWhite)
            )
        }
    }
}

@Composable
private fun MemberRow(member: Member) {
    Surface(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        color = CardWhite,
    ) {
        Column {
            Row(
                modifier = Modifier.padding(horizontal = 12.dp, vertical = 10.dp),
                verticalAlignment = Alignment.CenterVertically
            ) {
                // Avatar + name
                Row(
                    modifier = Modifier.weight(1.5f),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Box(
                        modifier = Modifier
                            .size(32.dp)
                            .clip(CircleShape)
                            .background(FinTrackBlue),
                        contentAlignment = Alignment.Center
                    ) {
                        Text(member.initials, color = Color.White, fontSize = 11.sp, fontWeight = FontWeight.Bold)
                    }
                    Spacer(Modifier.width(6.dp))
                    Text(member.name, fontSize = 12.sp, color = TextPrimary, fontWeight = FontWeight.Medium)
                }

                Text(member.email, fontSize = 11.sp, color = TextSecondary, modifier = Modifier.weight(2f))

                Box(modifier = Modifier.weight(1f)) {
                    val (bgColor, textColor) = when (member.role) {
                        MemberRole.OWNER -> FinTrackBlueSurface to FinTrackBlue
                        MemberRole.ADMIN -> IncomeGreenSurface to IncomeGreen
                        MemberRole.MEMBER -> SurfaceGray to TextSecondary
                    }
                    Box(
                        modifier = Modifier
                            .clip(RoundedCornerShape(4.dp))
                            .background(bgColor)
                            .padding(horizontal = 6.dp, vertical = 2.dp)
                    ) {
                        Text(
                            member.role.name.lowercase().replaceFirstChar { it.uppercase() },
                            fontSize = 10.sp,
                            color = textColor,
                            fontWeight = FontWeight.Medium
                        )
                    }
                }

                Text(member.joinedAt, fontSize = 11.sp, color = TextSecondary, modifier = Modifier.weight(1.2f))

                IconButton(onClick = {}, modifier = Modifier.size(24.dp)) {
                    Icon(Icons.Filled.MoreVert, null, tint = TextTertiary, modifier = Modifier.size(16.dp))
                }
            }
            HorizontalDivider(color = BorderGray, thickness = 0.5.dp)
        }
    }
}
